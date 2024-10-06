<?php

namespace App\UseCases\Reservation;

use App\Services\ReservationService;

class DeleteReservationUseCase
{
    protected $reservationService;

    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    public function execute($id)
    {
        $this->reservationService->deleteReservation($id);
    }
}