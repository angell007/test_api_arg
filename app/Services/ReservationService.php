<?php

namespace App\Services;

use App\Repositories\ReservationRepository;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    protected $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    public function getUserReservations($userId)
    {
        return $this->reservationRepository->getForUser($userId);
    }

    public function getReservation($id)
    {
        return $this->reservationRepository->findById($id);
    }

    public function createReservation(array $data)
    {
        $this->checkOverlap($data['space_id'], $data['start_time'], $data['end_time']);
        return $this->reservationRepository->create($data);
    }

    public function updateReservation($id, array $data)
    {
        $reservation = $this->reservationRepository->findById($id);
        $this->checkOverlap($data['space_id'], $data['start_time'], $data['end_time'], $id);
        return $this->reservationRepository->update($reservation, $data);
    }

    public function deleteReservation($id)
    {
        $reservation = $this->reservationRepository->findById($id);
        $this->reservationRepository->delete($reservation);
    }

    protected function checkOverlap($spaceId, $startTime, $endTime, $excludeReservationId = null)
    {
        if ($this->reservationRepository->checkOverlap($spaceId, $startTime, $endTime, $excludeReservationId)) {
            throw ValidationException::withMessages([
                'overlap' => ['Ya existe una reserva para este espacio en el horario seleccionado.'],
            ]);
        }
    }
}