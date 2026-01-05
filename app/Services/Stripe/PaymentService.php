<?php

declare(strict_types=1);

namespace App\Services\Stripe;

use CodeIgniter\I18n\Time;
use Config\EventSettings;
use Exception;
use Stripe\Checkout\Session;
use Stripe\Refund;

use function PHPSTORM_META\type;

class PaymentService extends BaseService
{
    public function createPaymentLink(array $seats): bool|string
    {
        try {
            
            $lineItems = [];
            $totalAmount = 0;
            $stripeAccountId = $seats[0]->stripe_account_id;

            foreach($seats as $seat){

                $totalAmount += intval($seat->price);

                $eventDate = Time::parse($seat->event_date)->format('d/m/T H:i');

                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'brl',
                        'product_data' => [
                            'name' => "Apresentação: {$eventDate}, Setor: {$seat->sector}, Fila: {$seat->row}, Assento: {$seat->number}, Tipo: {$seat->type()}",
                        ],
                        'unit_amount' => intval($seat->price) // preço em centavos
                    ],
                    'quantity' => 1
                ];
            }

            $session = Session::create(
                [
                    'payment_method_types' => ['card'],
                    'line_items' => $lineItems,
                    'mode' => 'payment', // define que é um pagamento e não uma assinatura
                    'success_url' => url_to('checkout.success'),
                    'cancel_url' => url_to('cart'),
                    'payment_intent_data' => [
                        'application_fee_amount' => config(EventSettings::class)->calculateServiceFee($totalAmount), // taxa de serviço da plataforma de vendas, o stripe ainda aplicará sua taxa descontada do valor repassado ao criador do evento
                    ]
                ],

                [
                    'stripe_account' => $stripeAccountId
                ]
            );

            session()->set('stripe_session_id', $session->id);
            session()->set('stripe_account_id', $stripeAccountId);

            $checkoutUrl = $session->url;

            if(!$checkoutUrl){
                throw new Exception("Não foi possível gerar a URL de checkout");
            }

            return $checkoutUrl;

            
        } catch (\Throwable $th) {
            log_message('error', '[STRIPE ERROR - CREATE CHECKOUT LINK] {exception}', ['exception' => $th]);
            return false;
        }

    }

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
