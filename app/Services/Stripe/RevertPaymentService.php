<?php

declare(strict_types=1);

namespace App\Services\Stripe;

use App\Models\OrderModel;
use App\Models\SeatBookingModel;
use CodeIgniter\I18n\Time;
use Config\EventSettings;
use Exception;
use Stripe\Checkout\Session;
use Stripe\Refund;

use function PHPSTORM_META\type;

class RevertPaymentService extends BaseService
{
    public function execute(string $paymentIntent, string $stripeAccountId, string $sessionStripeId): bool
    {
        try {
            
           $builder = model(OrderModel::class);

           $builder->db->transBegin();

            $builder->where('stripe_session_id', $sessionStripeId)->delete();

            model(SeatBookingModel::class)->where('payment_intent', $paymentIntent)->delete();

            $result = (new PaymentService)->refundPayment(paymentIntent: $paymentIntent, stripeAccountId: $stripeAccountId);

            if(! $result) {
                throw new Exception("Falha ao reembolsar o pagamento");
            }

           $result = $builder->db->transCommit();

           if(! $result) {
                throw new Exception("A transação para reverter a criação do pedido não foi finalizada");
            }

            return true;
            
        } catch (\Throwable $th) {

            // VOLTAR E FINALIZAR
            log_message('error', '[STRIPE ERROR - CREATE CHECKOUT LINK] {exception}', ['exception' => $th]);
            return false;
        }

    }

    // VOLTAR E FINALIZAR
    public function getSession(string $sessionId, string $stripeAccountId): Session
    {
        return Session::retrieve($sessionId, ['stripe_account' => $stripeAccountId]);
    }

    public function refundPayment(string $paymentIntent, string $stripeAccountId): bool
    {
        try {
            
            Refund::create(
                ['payment_intent' => $paymentIntent],
                ['stripe_account' => $stripeAccountId],
            );

            return true;
            
        } catch (\Throwable $th) {
            log_message('error', '[STRIPE ERROR - REFUND ] {exception}', ['exception' => $th]);
            return false;
        }
    }
   
}
