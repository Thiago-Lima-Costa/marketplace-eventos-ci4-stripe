<?php

namespace App\Validation;

class EventValidation
{
    public function getRules(): array
    {
        return [
            'name' => [
                'rules' => [
                    'required',
                    'max_length[255]',
                ],
                'errors' => [
                    'required' => 'O nome do evento é obrigatório',
                    'max_length' => 'O nome do evento deve ter no máximo 255 caractéres',
                ],
            ],

            'location' => [
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => 'A localização do evento é obrigatória',
                ],
            ],

            'start_date' => [
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => 'A data de início do evento é obrigatória',
                ],
            ],

            'end_date' => [
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => 'A data de término do evento é obrigatória',
                ],
            ],

            'description' => [
                'rules' => [
                    'required',
                ],
                'errors' => [
                    'required' => 'A descrição do evento é obrigatória',
                ],
            ],

            'image' => [
                'rules' => [
                    'uploaded[image]',
                    'ext_in[image,png,jpg,webp]',
                    'max_size[image,2048]'
                ],
                'errors' => [
                    'uploaded' => 'Erro no upload da imagem',
                    'ext_in' => 'Erro no upload da imagem',
                    'max_size' => 'Erro no upload da imagem',
                ],
            ],
        ];
    }
}
