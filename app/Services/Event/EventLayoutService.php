<?php

declare(strict_types=1);

namespace App\Services\Event;

use App\Models\RowModel;
use App\Models\SeatBookingModel;
use App\Models\SeatModel;
use App\Models\SectorModel;
use Exception;

class EventLayoutService
{
    /**
     * Organiza e retorna a estrutura de layout de um evento, agrupada por dias, incluindo setores, fileiras e assentos, além da disponibilidade dos assentos.
     *
     * @param integer $eventId O ID do evento
     * @return array Estrutura organizada, contendo os dias do evento com setores, fileiras e assentos para cada dia
     */
    public function build(int $eventId): array
    {
        $eventDays = db_connect()->table('event_days')
                                ->where('event_id', $eventId)
                                ->orderBy('event_date', 'ASC')
                                ->get()
                                ->getResult();

        if(empty($eventDays)){
            throw new Exception("O evento ID {$eventId} não tem dias de apresentação");
        }

        $sectors = model(SectorModel::class)
                        ->where('event_id', $eventId)
                        ->orderBy('name', 'ASC')
                        ->findAll();

        $rows = empty($sectors) ? [] : model(RowModel::class)
                                            ->whereIn('sector_id', array_column($sectors, 'id'))
                                            ->orderBy('id', 'ASC')
                                            ->findAll();

        $seats = empty($rows) ? [] : model(SeatModel::class)
                                            ->whereIn('row_id', array_column($rows, 'id'))
                                            ->orderBy('id', 'ASC')
                                            ->findAll();

        $bookingsDays = empty($seats) ? [] : model(SeatBookingModel::class)
                                            ->whereIn('event_day_id', array_column($eventDays, 'id'))
                                            ->findAll();        
                                            
        $tempBookings = [];

        foreach($bookingsDays as $booking) {

            $seatId = (int) $booking->seat_id;
            $eventDayId = (int) $booking->event_day_id;

            $tempBookings[$seatId][$eventDayId][] = $booking;

        }

        $seatsWithBookings = [];

        foreach($seats as $seat) {

            $seatCopy = clone $seat;
            $auxBookings = [];

            foreach($eventDays as $eventDay) {

                $eventDayId = (int) $eventDay->id;

                $auxBookings[$eventDayId] = $tempBookings[$seat->id][$eventDayId] ?? [];

            }

            $seatCopy->bookings = $auxBookings;

            $seatsWithBookings[] = $seatCopy;
            
        }

        foreach($rows as $row) {
            $row->seats = array_filter($seatsWithBookings, function ($seat) use ($row) {
                return (int) $seat->row_id === (int) $row->id;
            });
        }

        foreach($sectors as $sector) {
            $sector->rows = array_filter($rows, function ($row) use ($sector) {
                return (int) $row->sector_id === (int) $sector->id;
            });
        }

        foreach($eventDays as $day) {
            $day->sectors = $sectors;
        }

        return $eventDays;
    }
}
