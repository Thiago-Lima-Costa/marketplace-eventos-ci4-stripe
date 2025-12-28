<?php

namespace App\Models;

use App\Entities\OrderItem;
use App\Models\Basic\AppModel;

class OrderItemModel extends AppModel
{
    protected $table            = 'order_items';
    protected $returnType       = OrderItem::class;
    protected $allowedFields    = [
        'order_id',
        'booking_id',
        'price',
        'booking_data',
    ];

    protected $useTimestamps = false;

}
