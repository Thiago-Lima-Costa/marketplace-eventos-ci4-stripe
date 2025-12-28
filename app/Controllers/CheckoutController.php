<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\Cart\CartService;
use App\Services\Stripe\PaymentService;
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

        if(empty($seats)){
            return redirect()->route('cart')->with('info', 'Você não tem assentos reservados');
        }

        $checkoutUrl = $this->paymentService->createPaymentLink($seats);

        if(!is_string($checkoutUrl)){
            return redirect()->route('cart')->with('danger', 'Ocorreu um erro ao gerar a página de pagamento');
        }

        return redirect()->to($checkoutUrl);
    }

    public function success(): RedirectResponse
    {
        $sessionId = session('stripe_session_id');
        $stripeAccountId = session('stripe_account_id');

        if(!$sessionId || !$stripeAccountId){
            return redirect()->route('cart')->with('danger', 'Sessão de checkout não encontrada');
        }

        $stripeSession = $this->paymentService->getSession(
            sessionId: $sessionId, 
            stripeAccountId: $stripeAccountId
        );

        d($stripeSession->payment_status);
        dd($stripeSession);
    }
}
