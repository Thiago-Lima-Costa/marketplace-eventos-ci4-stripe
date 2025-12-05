<?php

declare(strict_types=1);

namespace App\Services\Cart;

use App\Enums\StatusSeatBooking;
use App\Models\SeatBookingModel;
use CodeIgniter\I18n\Time;

class CartService
{
    private SeatBookingModel $model;

    public function __construct()
    {
        $this->model = model(SeatBookingModel::class);
    }

    public function all(int $userId): array
    {
        return $this->getSeats($userId);
    }

    public function destroy(int $id): void
    {
        $this->model->where([
            'status' => StatusSeatBooking::Reserved->value,
            'user_id' => auth()->id(),
        ])->delete($id);
    }

    private function getSeats(int $userId): array
    {
        $this->model->select([
            'seat_bookings.*',
            'seats.number',
            'sectors.name AS sector',
            'rows.name AS row',
            'events.id AS event_id',
            'events.user_id',
            'events.name AS event',
            'event_days.event_date',
            'users.stripe_account_id',
        ]);
        $this->model->join('seats', 'seats.id = seat_bookings.seat_id');
        $this->model->join('rows', 'rows.id = seats.row_id');
        $this->model->join('sectors', 'sectors.id = rows.sector_id');
        $this->model->join('events', 'events.id = sectors.event_id');
        $this->model->join('users', 'users.id = events.user_id');
        $this->model->join('event_days', 'event_days.id = seat_bookings.event_day_id');
        $this->model->where('seat_bookings.user_id', $userId);
        $this->model->where('seat_bookings.status', StatusSeatBooking::Reserved->value);
        $this->model->where('seat_bookings.expire_at >', Time::now()->toDateTimeString());
        $this->model->groupBy('seat_bookings.id');
        $this->model->orderBy('seat_bookings.expire_at', 'DESC');
        
        return $this->model->findAll();
    }
}
