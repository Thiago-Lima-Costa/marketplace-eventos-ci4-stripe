<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Entities\Event;
use App\Models\EventModel;
use App\Services\Event\EventStoreService;
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

    public function show(string $code)
    {
        $event = $this->model->whereUser()->getByCode(code: $code);

        $data = [
            'title' => 'Detalhes do evento',
            'event' => $event,
        ];

        return view('Dashboard/Events/show', $data);
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

        $result = (new EventStoreService)->create();

        if(! $result instanceof Event) {
            return $this->failServerError(description: 'Erro ao criar evento', code: 500, message: 'Erro ao criar evento');
        }

        session()->setFlashdata('success', 'Sucesso');

        return $this->respondCreated(data: [
            'success' => true,
            'redirect' => route_to('dashboard.events.show', $result->code),
            'message' => 'Sucesso',
        ]);
    }
}
