<?php

namespace App\Models;

use App\Entities\SeatBooking;
use App\Models\Basic\AppModel;

class SeatBookingModel extends AppModel
{
    public function __construct()
    {
        parent::__construct();

        $this->beforeInsert = array_merge($this->beforeInsert, ['setUserId']);
    }


    protected $table            = 'seat_bookings';
    protected $returnType       = SeatBooking::class;
    protected $allowedFields    = [
        'seat_id',
        'event_day_id',
        'user_id',
        'payment_intent',
        'status',
        'expire_at',
        'type',
        'price',
    ];

    protected $useTimestamps = false;

}
