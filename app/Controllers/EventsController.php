<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Event;
use App\Models\EventModel;
use App\Validation\EventValidation;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class EventsController extends BaseController
{
    use ResponseTrait;

    private EventModel $model;

    public function __construct()
    {
        $this->model = model(EventModel::class);
    }

    public function index()
    {
        $data = [
            'title' => 'Meus Eventos',
            'events' => $this->model->whereUser()->orderBy('name', 'ASC')->findAll(),
        ];

        return view('Dashboard/Events/index', $data);
    }

    public function new()
    {
        $data = [
            'title' => 'Criar novo evento',
            'route' => route_to('dashboard.events.create')
        ];

        return view('Dashboard/Events/form', $data);
    }

    public function create(): ResponseInterface
    {
        $rules = (new EventValidation)->getRules();

        if(! $this->validate($rules)) {
            return $this->respond([
                'token' => csrf_hash(), 
                'success' => false,
                'errors' => $this->validator->getErrors(),
            ], 400, 'Erros de validação');
        }

        $inputRequest = $this->request->getPost;

        echo '<pre>';
        print_r($inputRequest);
        exit;

        // $data = [
        //     'title' => 'Criar novo evento',
        //     'route' => route_to('dashboard.events.create')
        // ];

        // return view('Dashboard/Events/form', $data);
    }
}
