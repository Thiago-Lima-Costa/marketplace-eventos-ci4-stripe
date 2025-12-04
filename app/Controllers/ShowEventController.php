<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\EventModel;
use CodeIgniter\HTTP\ResponseInterface;

class ShowEventController extends BaseController
{
    public function index(string $code)
    {
        $event = model(EventModel::class)->whereValidDates()->getByCode(code: $code);

        $data = [
            'title' => $event->name,
            'event' => $event,
        ];

        return view('Events/index', $data);
    }
}
