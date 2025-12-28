<?php
declare(strict_types=1);

namespace App\Services\Order;

use App\Entities\Order;
use App\Models\OrderItemModel;
use App\Models\OrderModel;
use App\Models\SeatBookingModel;
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
     * @param boolean $testModel indica se é modo teste, caso sim, lança uma exceção
     * @throws Exception
     * @return boolean|Order O pedido ou false em caso de erro
     */
    public function create(StripeSession $session, array $seats, bool $testModel = false): bool|Order
    {
        return false;
    }
}