<?php

namespace App\UseCases\Reservation;

use App\Services\ReservationService;

class CreateReservationUseCase
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function execute(array $data)
    {
        return $this->reservationService->createReservation($data);
    }
}