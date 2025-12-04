<?php

declare(strict_types=1);

namespace App\Services\Event;

use App\Entities\Sector;
use CodeIgniter\I18n\Time;

class SeatRenderService
{
    private int|null $loggedUserId = null;

    public function __construct()
    {
        $this->loggedUserId = auth()->loggedIn() ? (int) auth()->id() : null;
    }

    /**
     * Renderiza a tabela de setores, fileiras e assentos de um evento.
     *
     * @param array $layoutDays Dados dos setores contendo fileiras e assentos como objetos, para cada dia.
     * @param int|null $loggedUserId ID do usuário logado, quando for o caso.
     * @return string HTML gerado com os assentos organizados por dia de apresentação, setores, fileiras e assentos.
     */
    public function render(array $layoutDays): string
    {
        $html = '';

        $html .= <<<ACCORDION
            <div class="accordion accordion-flush" id="event-days">
        ACCORDION;

        foreach ($layoutDays as $day) {

            $eventDateFormatted = Time::parse($day->event_date)->format('d/m/Y H:i');

            $html .= <<<ACCORDION
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne-{$day->id}" aria-expanded="false" aria-controls="flush-collapseOne-{$day->id}">
                    Apresentação {$eventDateFormatted}
                </button>
                </h2>
                <div id="flush-collapseOne-{$day->id}" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        {$this->renderSections(sectors:$day->sectors, eventDayId: (int)$day->id)}
                    </div>
                </div>
            </div>  
            ACCORDION;
        }

        $html .= <<<ACCORDION
            </div>
        ACCORDION;

        return $html;
    }

    /**
     * Renderiza os setores, fileiras e assentos de um evento.
     *
     * @param array $sectors
     * @param integer $eventDayId ID do dia do evento
     * @return string
     */
    public function renderSections(array $sectors, int $eventDayId): string
    {
        $html = '';

        $html .= <<<ACCORDION
            <div class="accordion accordion-flush" id="sectors">
        ACCORDION;

        foreach ($sectors as $sector) {

            $totals = $this->calculateSeatsTotals($sector);

            $seatsHTML = $this->renderSeatsForSector(sector: clone $sector, eventDayId: $eventDayId);

            $html .= <<<ACCORDION
            <div class="accordion-item">
                <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne-{$eventDayId}-{$sector->id}" aria-expanded="false" aria-controls="flush-collapseOne-{$eventDayId}-{$sector->id}">
                    Setor {$sector->name}
                </button>
                </h2>
                <div id="flush-collapseOne-{$eventDayId}-{$sector->id}" class="accordion-collapse collapse" data-bs-parent="#sectors">
                    <div class="accordion-body">
                       <strong>Preço integral: </strong> {$sector->ticketPrice()}</br>
                       <strong>Preço meia entrada: </strong> {$sector->discountedPrice()}</br>
                       <strong>Total de assentos: </strong> {$totals['total']}</br>

                        <div class="table-responsive mb-3">
                            <table class="table table-borderless table-sm sector-table">
                                <thead>
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {$seatsHTML}
                                </tbody>
                            </table>
                        </div>   
                    </div>
                </div>
            </div>  
            ACCORDION;
        }

        $html .= <<<ACCORDION
            </div>
        ACCORDION;

        return $html;
    }

    /**
     * Renderiza a tabela de assentos de um setor, considerando as reservas por dia do evento
     *
     * @param Sector $sector
     * @param integer $eventDayId ID do dia do evento.
     * @return string HTML gerado para os assentos.
     */
    public function renderSeatsForSector(Sector $sector, int $eventDayId): string
    {
        $seatsHTML = '';

        $sectorInitials = implode('', array_map(fn($word) => strtoupper($word[0]), explode(' ', $sector->name)));

        foreach($sector->rows as $row){

            $rowHTML = <<< TABLE
                <td><strong>{$row->name}</strong></td>
            TABLE;

            foreach($row->seats as $seat){
                $seatCode = base64_encode("{$eventDayId}-{$sector->id}-{$row->id}-{$seat->number}");

                $dayBookings = $seat->bookings[$eventDayId] ?? [];

                $btnClass = 'btn-dark btn-seat';
                $title = 'Disponível';
                $seatCodeAttribute = "data-seat={$seatCode}";

                foreach($dayBookings as $booking){
 
                    if($booking->isSold() || $booking->isPending()) {
                        $btnClass = 'btn-danger';
                        $title = $booking->status();
                        $seatCodeAttribute = '';
                        break;
                    }

                    if($booking->isReserved()){
                       
                        $btnClass = 'btn-warning text-dark ';
                        $btnClass .= $this->loggedUserId === (int) $booking->user_id ? 'btn-seat seat-session-reserved' : '';
                        $title = $this->loggedUserId === (int) $booking->user_id ? "Esse assento ficará reservado para você até: {$booking->expire_at}" : $booking->status();
                        break;
                    }
                }

                $seatLabel = "{$sectorInitials}<br>{$row->name}<br>A-{$seat->number}";

                $rowHTML .= <<<TABLE
                    <td>
                        <div title="{$title}" {$seatCodeAttribute} class="{$btnClass} btn btn-sm badge">
                            {$seatLabel}
                        </div>
                    </td>
                TABLE;

            }

            $seatsHTML .= <<< TABLE
                <tr>{$rowHTML}</tr>
            TABLE;
            
        }

        return $seatsHTML;
    }

    private function calculateSeatsTotals(Sector $sector): array
    {
        return ['total' => array_reduce($sector->rows, fn($carry, $row) => $carry + count($row->seats), 0)];
    }
}
