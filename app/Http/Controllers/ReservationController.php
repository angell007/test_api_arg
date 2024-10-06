<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\UseCases\Reservation\CreateReservationUseCase;
use App\UseCases\Reservation\UpdateReservationUseCase;
use App\UseCases\Reservation\DeleteReservationUseCase;
use App\UseCases\Reservation\GetReservationsUseCase;

class ReservationController extends Controller
{
    /**
     * Listar reservaciones
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "space_id": 1,
     *      "user_id": 1,
     *      "start_time": "2023-05-01 09:00:00",
     *      "end_time": "2023-05-01 11:00:00",
     *      "description": "Reunión de equipo"
     *    },
     *    {
     *      "id": 2,
     *      "space_id": 2,
     *      "user_id": 2,
     *      "start_time": "2023-05-02 14:00:00",
     *      "end_time": "2023-05-02 16:00:00",
     *      "description": "Entrevista de trabajo"
     *    }
     *  ]
     * }
     */
    public function index(GetReservationsUseCase $useCase)
    {
        $reservations = $useCase->execute(auth()->id());
        return response()->json($reservations);
    }

    /**
     * Crear una nueva reservación
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @bodyParam space_id integer required El ID del espacio a reservar. Example: 1
     * @bodyParam start_time datetime required La fecha y hora de inicio de la reservación. Example: 2023-05-03 10:00:00
     * @bodyParam end_time datetime required La fecha y hora de fin de la reservación. Example: 2023-05-03 12:00:00
     * @bodyParam description string La descripción de la reservación. Example: Presentación de proyecto
     * 
     * @response {
     *  "data": {
     *    "id": 3,
     *    "space_id": 1,
     *    "user_id": 1,
     *    "start_time": "2023-05-03 10:00:00",
     *    "end_time": "2023-05-03 12:00:00",
     *    "description": "Presentación de proyecto"
     *  }
     * }
     */
    public function store(ReservationRequest $request, CreateReservationUseCase $useCase)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $reservation = $useCase->execute($data);
        return response()->json($reservation, 201);
    }

    /**
     * Actualizar una reservación
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @bodyParam space_id integer required El ID del espacio a reservar. Example: 1
     * @bodyParam start_time datetime required La fecha y hora de inicio de la reservación. Example: 2023-05-03 10:00:00
     * @bodyParam end_time datetime required La fecha y hora de fin de la reservación. Example: 2023-05-03 12:00:00
     * @bodyParam description string La descripción de la reservación. Example: Presentación de proyecto
     * 
     * @response {
     *  "data": {
     *    "id": 3,
     *    "space_id": 1,
     *    "user_id": 1,
     *    "start_time": "2023-05-03 10:00:00",
     *    "end_time": "2023-05-03 12:00:00",
     *    "description": "Presentación de proyecto"
     *  }
     * }
     */
    public function update(ReservationRequest $request, $id, UpdateReservationUseCase $useCase)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $reservation = $useCase->execute($id, $data);
        return response()->json($reservation);
    }

    /**
     * Eliminar una reservación
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @response {
     *  "message": "Reservación eliminada exitosamente"
     * }
     */
    public function destroy($id, DeleteReservationUseCase $useCase)
    {
        $useCase->execute($id);
        return response()->json(null, 204);
    }
}