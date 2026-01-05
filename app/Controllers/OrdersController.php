<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use CodeIgniter\HTTP\ResponseInterface;

class OrdersController extends BaseController
{
    private OrderModel $model;

    public function __construct()
    {
        $this->model = model(OrderModel::class);
    }

    public function index()
    {
        //
    }

    public function show(string $code)
    {
        $order = $this->model->whereUser()->getByCode(code: $code);

        dd($order);
    }
}
