<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpaceRequest;
use App\UseCases\Space\CreateSpaceUseCase;
use App\UseCases\Space\UpdateSpaceUseCase;
use App\UseCases\Space\DeleteSpaceUseCase;
use App\UseCases\Space\GetSpacesUseCase;
use App\UseCases\Space\GetSpaceUseCase;

/**
 * @group Espacios
 * 
 * Endpoints para manejar los espacios
 */
class SpaceController extends Controller
{
    /**
     * Listar espacios
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @response {
     *  "data": [
     *    {
     *      "id": 1,
     *      "name": "Sala de Conferencias A",
     *      "description": "Sala grande con capacidad para 50 personas",
     *      "capacity": 50,
     *      "type": "room"
     *    },
     *    {
     *      "id": 2,
     *      "name": "Sala de Reuniones B",
     *      "description": "Sala mediana con capacidad para 20 personas",
     *      "capacity": 20,
     *      "type": "meeting_room"
     *    }
     *  ]
     * }
     */
    public function index(GetSpacesUseCase $useCase)
    {
        $spaces = $useCase->execute();
        return response()->json($spaces);
    }

    /**
     * Crear un nuevo espacio
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @bodyParam name string required El nombre del espacio. Example: Sala de Juntas C
     * @bodyParam description string required La descripción del espacio. Example: Sala pequeña para reuniones ejecutivas
     * @bodyParam capacity integer required La capacidad del espacio. Example: 10
     * 
     * @response {
     *  "data": {
     *    "id": 3,
     *    "name": "Sala de Juntas C",
     *    "description": "Sala pequeña para reuniones ejecutivas",
     *    "capacity": 10,
     *    "type": "desk"
     *  }
     * }
     */
    public function store(SpaceRequest $request, CreateSpaceUseCase $useCase)
    {
        $space = $useCase->execute($request->validated());
        return response()->json($space, 201);
    }

    /**
     * Actualizar un espacio existente
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @urlParam id integer required El ID del espacio a actualizar. Example: 3
     * 
     * @bodyParam name string required El nombre del espacio. Example: Sala de Juntas C
     * @bodyParam description string required La descripción del espacio. Example: Sala pequeña para reuniones ejecutivas
     * @bodyParam capacity integer required La capacidad del espacio. Example: 10
     * 
     * @response {
     *  "data": {
     *    "id": 3,
     *    "name": "Sala de Juntas C",
     *    "description": "Sala pequeña para reuniones ejecutivas",
     *    "capacity": 10,
     *    "type": "desk"
     *  }
     * }
     */
    public function update(SpaceRequest $request, $id, UpdateSpaceUseCase $useCase)
    {
        $space = $useCase->execute($id, $request->validated());
        return response()->json($space);
    }

    /**
     * Eliminar un espacio
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @urlParam id integer required El ID del espacio a actualizar. Example: 3
     * 
     * @response {
     *  "message": "Espacio eliminado exitosamente"
     * }
     */
    public function destroy($id, DeleteSpaceUseCase $useCase)
    {
        $useCase->execute($id);
        return response()->json(null, 204);
    }

    /**
     * Obtener detalles de un espacio
     * 
     * @authenticated
     * 
     * @header Authorization Bearer {token}
     * 
     * @urlParam id integer required El ID del espacio a actualizar. Example: 3
     * 
     * @response {
     *  "data": {
     *    "id": 1,
     *    "name": "Sala de Conferencias A",
     *    "description": "Sala grande con capacidad para 50 personas",
     *    "capacity": 50,
     *    "type": "room"
     *  }
     * }
     */
    public function show($id, GetSpaceUseCase $useCase)
    {
        $space = $useCase->execute($id);
        return response()->json($space);
    }
}
