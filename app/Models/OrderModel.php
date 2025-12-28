<?php

namespace App\Models;

use App\Entities\Order;
use App\Models\Basic\AppModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class OrderModel extends AppModel
{
    public function __construct()
    {
        parent::__construct();

        $this->beforeInsert = array_merge($this->beforeInsert, ['setUserId', 'setCode']);
    }

    protected $table            = 'orders';
    protected $returnType       = Order::class;
    protected $allowedFields    = [
        'user_id',
        'event_id',
        'stripe_session_id',
        'status',
        'total',
    ];

    public function getByCode(string $code): Order
    {
        return $this->where('code', $code)->first() ?? throw new PageNotFoundException("Pedido {$code} não encontrado");
    }

}
