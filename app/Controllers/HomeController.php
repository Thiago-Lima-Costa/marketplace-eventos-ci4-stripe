<?php

namespace App\Controllers;

use App\Models\EventModel;

class HomeController extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Home',
            'events' => model(EventModel::class)->getValidEvents(),
        ];

        return view('Home/index', $data);
    }
}
