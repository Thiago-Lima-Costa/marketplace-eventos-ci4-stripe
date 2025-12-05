<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Services\Cart\CartService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class CartController extends BaseController
{
    private CartService $service;

    public function __construct()
    {
        $this->service = new CartService;
    }

    public function index()
    {
        $seats = $this->service->all(auth()->id());

        $data = [
            'title' => 'Meu carrinho de compras',
            'seats' => $seats
        ];

        return view('Cart/index', $data);
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->service->destroy($id);
        return redirect()->back()->with('success', 'Sucesso');
    }
}
