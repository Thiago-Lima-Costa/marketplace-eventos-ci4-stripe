<?php

namespace App\Models;

use App\Entities\Event;
use App\Models\Basic\AppModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class EventModel extends AppModel
{
    public function __construct()
    {
        parent::__construct();

        $this->beforeInsert = array_merge($this->beforeInsert, ['setUserId', 'setCode']);
    }

    protected $table            = 'events';
    protected $returnType       = Event::class;
    protected $allowedFields    = [
        'name',
        'image',
        'location',
        'description',
        'start_date',
        'end_date',
    ];

    public function getByCode(string $code): Event
    {
        return $this->where('code', $code)->first() ?? throw new PageNotFoundException("Evento {$code} não encontrado");
    }
}
