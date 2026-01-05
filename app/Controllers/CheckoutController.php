<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Order;
use App\Services\Cart\CartService;
use App\Services\Order\OrderStoreService;
use App\Services\Stripe\PaymentService;
use App\Services\Stripe\RevertPaymentService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class CheckoutController extends BaseController
{
    private PaymentService $paymentService;
    private CartService $cartService;

    public function __construct()
    {
        $this->paymentService = new PaymentService;
        $this->cartService = new CartService;
    }

    public function index(): RedirectResponse
    {
        $seats = $this->cartService->all(auth()->id());

        if (empty($seats)) {
            return redirect()->route('cart')->with('info', 'Você não tem assentos reservados');
        }

        $checkoutUrl = $this->paymentService->createPaymentLink($seats);

        if (!is_string($checkoutUrl)) {
            return redirect()->route('cart')->with('danger', 'Ocorreu um erro ao gerar a página de pagamento');
        }

        return redirect()->to($checkoutUrl);
    }

    public function success(): RedirectResponse
    {
        $sessionId = session('stripe_session_id');
        $stripeAccountId = session('stripe_account_id');

        if (!$sessionId || !$stripeAccountId) {
            return redirect()->route('cart')->with('danger', 'Sessão de checkout não encontrada');
        }

        $stripeSession = $this->paymentService->getSession(
            sessionId: $sessionId,
            stripeAccountId: $stripeAccountId
        );

        $seats = $this->cartService->all(auth()->id());

        $orderStoreService = new OrderStoreService;

        // DEBUG
        $testMode = false;

        $result = $orderStoreService->create(session: $stripeSession, seats: $seats, testMode: $testMode);

        if (! $result instanceof Order) {

            $successRevert = (new RevertPaymentService)->execute(paymentIntent: $stripeSession->payment_intent, stripeAccountId: $stripeAccountId, sessionStripeId: $stripeSession->id);

            if (! $successRevert) {
                return redirect()->route('cart')->with('danger', 'Ocorreu um erro ao criar o pedido e não conseguimos realizar o seu reembolso, por favor entre em contato com o suporte');
            }

            return redirect()->route('cart')->with('danger', 'Ocorreu um erro ao realizar o pedido, Já realizamos o reembolso do valor');
        }

        session()->remove('stripe_session_id');
        session()->remove('stripe_account_id');

        return redirect()->route('dashboard.orders.show', [$result->code])->with('success', 'Sucesso');
    }
}
