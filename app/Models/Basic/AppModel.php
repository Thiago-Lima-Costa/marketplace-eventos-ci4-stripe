<?php

namespace App\Models\Basic;

use CodeIgniter\Model;

abstract class AppModel extends Model
{
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected bool $updateOnlyChanged = false;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['escapeData'];
    protected $beforeUpdate   = ['escapeData'];

    protected function escapeData(array $data): array
    {
        return esc($data);
    }

    protected function setUserId(array $data): array
    {
        $data['data']['user_id'] = auth()->id();
        return $data;
    }

     protected function setCode(array $data): array
    {
        do {
           
            $code = rand(10000000, 99999999);
            $result = $this->select('code')->where('code', $code)->countAllResults();
            
        } while ($result > 0);

        $data['data']['code'] = $code;

        return $data;
    }

    public function whereUser(): self
    {
        $this->where("{$this->table}.user_id", auth()->id());
        return $this;
    }
}
