<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Entities\SeatBooking;
use App\Enums\StatusSeatBooking;
use App\Models\SeatBookingModel;
use App\Models\SeatModel;
use App\Models\SectorModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;
use Config\EventSettings;

class ApiReserveSeatsController extends BaseController
{
    use ResponseTrait;

    private SeatBookingModel $model;

    public function __construct()
    {
        $this->model = model(SeatBookingModel::class);
    }

    public function action(): ResponseInterface
    {
        $isLoggedId = auth()->loggedIn();

        if (!$isLoggedId) {
            return $this->respond([
                'success' => true,
                'is_logged_in' => $isLoggedId,
                'message' => 'Usuário não autenticado'
            ], ResponseInterface::HTTP_OK);
        }

        $inputRequest = esc($this->request->getJSON(true));

        $seatCode = $inputRequest['seat_code'] ?? null;
        $seatType = $inputRequest['seat_type'] ?? null;

        if (!$seatCode || !$seatType) {
            return $this->respond([
                'success' => false,
                'is_logged_in' => $isLoggedId,
                'message' => 'Código do assento ou tipo não foi fornecido'
            ], ResponseInterface::HTTP_OK);
        }

        $decodedSeat = base64_decode($seatCode);
        $seatParts = explode('-', $decodedSeat);

        if (count($seatParts) !== 4) {
            return $this->respond([
                'success' => false,
                'is_logged_in' => $isLoggedId,
                'message' => 'Código do assento inválido'
            ], ResponseInterface::HTTP_OK);
        }

        [$eventDayId, $sectorId, $rowId, $seatNumber] = $seatParts;

        $seat = model(SeatModel::class)->where(['row_id' => $rowId, 'number' => $seatNumber])->first();

        if (! $seat) {
            return $this->respond([
                'success' => false,
                'is_logged_in' => $isLoggedId,
                'message' => 'Assento não encontrado'
            ], ResponseInterface::HTTP_NOT_FOUND);
        }

        $existingReservations = $this->model->where([
            'seat_id' => $seat->id,
            'event_day_id' => $eventDayId,
            'status' => StatusSeatBooking::Reserved->value,
            'expire_at >' => Time::now()->toDateTimeString(),
            'user_id !=' => auth()->id()
        ])->first();

        if ($existingReservations) {
            return $this->respond([
                'success' => false,
                'is_logged_in' => $isLoggedId,
                'message' => 'Que pena, este assento está reservado'
            ], status: ResponseInterface::HTTP_BAD_REQUEST, message: 'Que pena, este assento está reservado');
        }

        $result = $this->toggleSeatReservation(
            seatId: $seat->id,
            eventDayId: $eventDayId,
            sectorId: $sectorId,
            seatType: $seatType
        );

        return $this->respond([
            'success' => $result['success'],
            'is_logged_in' => $isLoggedId,
            'message' => $result['message']
        ], ResponseInterface::HTTP_OK);
    }

    private function toggleSeatReservation(int $seatId, int $eventDayId, int $sectorId, string $seatType): array
    {
        $existingReservation = $this->model->where([
            'seat_id' => $seatId,
            'event_day_id' => $eventDayId,
            'status' => StatusSeatBooking::Reserved->value,
            'expire_at >' => Time::now()->toDateTimeString(),
            'user_id' => auth()->id()
        ])->first();

        if ($existingReservation) {
            $this->model->delete($existingReservation->id, true);

            return [
                'success' => true,
                'message' => 'Reserva removida com sucesso'
            ];
        }

        $sector = model(SectorModel::class)->where(['id' => $sectorId])->first();

        $this->model->insert(new SeatBooking([
            'seat_id' => $seatId,
            'event_day_id' => $eventDayId,
            'status' => StatusSeatBooking::Reserved->value,
            'type' => $seatType,
            'price' => $seatType === 'half' ? $sector->discounted_price : $sector->ticket_price,
            'expire_at' => config(EventSettings::class)->getReservationTime(),
            'user_id' => auth()->id()
        ]));

        return [
            'success' => true,
            'message' => 'Assento reservado com sucesso'
        ];
    }
}
