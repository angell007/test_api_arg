<?php

namespace App\Repositories;

use App\Models\Space;

class SpaceRepository
{
    public function getAll()
    {
        $startDate = request()->get('startDate', null);
        $endDate = request()->get('endDate', null);
        $capacity = request()->get('capacity', null);
        $type = request()->get('type', null);

        $spaces = Space::query();

        if ($capacity) {
            $spaces->where('capacity', '>=', $capacity);
        }

        if ($type) {
            $spaces->where('type', $type);
        }

        if ($startDate && $startDate != 'null' && $endDate && $endDate != 'null') {
            $startDateTime = new \DateTime($startDate);
            $endDateTime = new \DateTime($endDate);

            $spaces->whereDoesntHave('reservations', function ($query) use ($startDateTime, $endDateTime) {
                $query->where(function ($q) use ($startDateTime, $endDateTime) {
                    $q->whereBetween('start_time', [$startDateTime, $endDateTime])
                      ->orWhereBetween('end_time', [$startDateTime, $endDateTime])
                      ->orWhere(function ($q) use ($startDateTime, $endDateTime) {
                          $q->where('start_time', '<=', $startDateTime)
                            ->where('end_time', '>=', $endDateTime);
                      });
                });
            });
        }

        return $spaces->get();
    }

    public function findById($id)
    {
        return Space::findOrFail($id);
    }

    public function create(array $data)
    {
        return Space::create($data);
    }

    public function update(Space $space, array $data)
    {
        $space->update($data);
        return $space;
    }

    public function delete(Space $space)
    {
        $space->delete();
    }
}
