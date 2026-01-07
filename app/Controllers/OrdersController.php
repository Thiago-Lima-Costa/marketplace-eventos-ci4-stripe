<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use CodeIgniter\HTTP\ResponseInterface;
use Dompdf\Dompdf;


class OrdersController extends BaseController
{
    private OrderModel $model;

    public function __construct()
    {
        $this->model = model(OrderModel::class);
    }

    public function index()
    {
       $data = [
        'title' => 'Meus pedidos',
        'orders' => $this->model->whereUser()->orderBy('created_at', 'DESC')->findAll(),
       ];

       return view('Dashboard/Orders/index', $data);
    }

    public function show(string $code)
    {
        $order = $this->model->whereUser()->getByCode(code: $code);

        $data = [
        'title' => 'Detalhes do pedido',
        'order' => $order,
       ];

       return view('Dashboard/Orders/show', $data);
    }

    public function print(string $code)
    {
        $order = $this->model->whereUser()->getByCode(code: $code, withSeats: true);

        if(! $order->isPaid()){
            return redirect()->back()->with('danger', 'Esse pedido não foi pago');
        }

        $data = [
        'title' => 'Imprimir ingressos',
        'order' => $order,
       ];

       $view = view('Dashboard/Orders/print', $data);

       $fileName = "Pedido-{$order->code}";

       $dompdf = new Dompdf();
       $dompdf->loadHtml($view);
       $dompdf->setPaper('A4', 'portrait');
       $dompdf->render();
       $dompdf->stream($fileName, ['Attachment' => false]);
    }
}
