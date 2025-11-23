<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Services\Event\EventLayoutService;
use App\Services\Event\SeatRenderService;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class ApiEventLayoutController extends BaseController
{
    use ResponseTrait;

    public function layout(string $code)
    {
        $event = model(EventModel::class)->getByCode(code: $code);
        $layoutDays = (new EventLayoutService)->build($event->id);
        $structure = (new SeatRenderService)->render($layoutDays);

        return $this->respond(data: ['structure' => $structure], status: 200, message: 'Layout do evento') ;
    }
}
