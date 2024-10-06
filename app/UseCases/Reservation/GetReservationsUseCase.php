<?php

namespace App\UseCases\Reservation;

use App\Services\ReservationService;

class GetReservationsUseCase
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function execute($userId)
    {
        return $this->reservationService->getUserReservations($userId);
    }
}