<?php

namespace App\UseCases\Space;

use App\Services\SpaceService;

class UpdateSpaceUseCase
{
    protected $spaceService;

    public function __construct(SpaceService $spaceService)
    {
        $this->spaceService = $spaceService;
    }

    public function execute($id, array $data)
    {
        return $this->spaceService->updateSpace($id, $data);
    }
}