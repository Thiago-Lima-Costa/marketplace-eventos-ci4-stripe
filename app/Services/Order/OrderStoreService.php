<?php
declare(strict_types=1);

namespace App\Services\Order;

use App\Entities\Order;
use App\Entities\OrderItem;
use App\Enums\StatusSeatBooking;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\SeatBookingModel;
use Exception;
use Stripe\Checkout\Session as StripeSession;

class OrderStoreService
{
    private OrderModel $orderModel;
    private OrderItemModel $orderItemModel;
    private SeatBookingModel $seatBookingModel;

    public function __construct()
    {
        $this->orderModel = model(OrderModel::class);
        $this->orderItemModel = model(OrderItemModel::class);
        $this->seatBookingModel = model(SeatBookingModel::class);
    }

    /**
     * Processa a criação do pedido
     *
     * @param StripeSession $session
     * @param array $seats Assentos reservados
     * @param boolean $testMode indica se é modo teste, caso sim, lança uma exceção
     * @throws Exception
     * @return boolean|Order O pedido ou false em caso de erro
     */
    public function create(StripeSession $session, array $seats, bool $testMode = false): bool|Order
    {
        try {
           
            if(empty($seats)){
                throw new Exception("Não há assentos reservados para a criação do pedido");
            }

            $this->orderModel->db->transBegin();

            $order = new Order();
            $order->event_id = $seats[0]->event_id;
            $order->stripe_session_id = $session->id;
            $order->status = $session->payment_status;
            $order->total = intval($session->amount_total);

            $id = $this->orderModel->insert($order);

            /** @var SeatBooking $booking */
            foreach($seats as $booking) {

                $orderItemId = $this->orderItemModel
                                    ->allowCallbacks(false)
                                    ->insert(new OrderItem(
                                        [
                                            'order_id' => $id,
                                            'booking_id' => $booking->id,
                                            'price' => $booking->price,
                                            'booking_data' => [
                                                'event' => $booking->event,
                                                'event_date' => $booking->event_date,
                                                'sector' => $booking->sector,
                                                'row' => $booking->row,
                                                'number' => $booking->number,
                                                'type' => $booking->type,
                                            ]
                                        ]
                                    ));

                if(! $orderItemId){
                    throw new Exception("Não foi possível criar o item do pedido");
                }

                $seatStatus = $order->isPaid() ? StatusSeatBooking::Sold->value : StatusSeatBooking::Pending->value;

                $bookingToUpdate = [
                    'status' => $seatStatus,
                    'payment_intent' => $session->payment_intent
                ];

                $success = $this->seatBookingModel->update($booking->id, $bookingToUpdate);

                if(! $success){
                    throw new Exception("Não foi possível atualizar o assento");
                }

            }

            $result = $this->orderModel->db->transCommit();

            if(!$result){
                throw new Exception("A transação para a criação do pedido falhou");
            }

             if($testMode){
                throw new Exception("Lançando exceção para teste de criação do pedido");
            }

            return $this->orderModel->find($id);
            
        } catch (\Throwable $th) {
             log_message('error', '[ERROR - ORDER CREATE] {exception}', ['exception' => $th]);
            return false;
        }

    }
}