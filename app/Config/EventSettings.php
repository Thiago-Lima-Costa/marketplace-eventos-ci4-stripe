<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\I18n\Time;

class EventSettings extends BaseConfig
{
    public float $administrationFee = 0.03;

    /**
     * Calcula a taxa de serviço
     *
     * @param integer $amount Valor da venda em centavos
     * @return integer Valor da taxa de serviço em centavos
     */
    public function calculateServiceFee(int $amount): int
    {
        return intval($this->administrationFee * $amount);
    }


    /**
     * Retorna a data de expiração da reserva dos assentos a partir de agora
     *
     * @return string
     */
    public function getReservationTime(): string
    {
        return Time::now()->addMinutes(60)->toDateTimeString();
    }
}
