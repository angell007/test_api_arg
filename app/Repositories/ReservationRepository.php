<?php

namespace App\Repositories;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class ReservationRepository
{
    public function getForUser($userId)
    {
        return Reservation::with('space', 'user')->where('user_id', $userId)->get();
    }

    public function findById($id)
    {
        return Reservation::findOrFail($id);
    }

    public function create(array $data)
    {
        return Reservation::create($data);
    }

    public function update(Reservation $reservation, array $data)
    {
        $reservation->update($data);
        return $reservation;
    }

    public function delete(Reservation $reservation)
    {
        $reservation->delete();
    }

    public function checkOverlap($spaceId, $startTime, $endTime, $excludeReservationId = null)
    {
        $query = Reservation::where('space_id', $spaceId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            });

        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        return $query->exists();
    }
}