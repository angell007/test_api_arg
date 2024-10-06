<?php

namespace App\UseCases\Reservation;

use App\Services\ReservationService;

class UpdateReservationUseCase
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function execute($id, array $data)
    {
        return $this->reservationService->updateReservation($id, $data);
    }
}