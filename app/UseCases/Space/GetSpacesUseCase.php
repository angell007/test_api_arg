<?php

namespace App\UseCases\Space;

use App\Services\SpaceService;

class GetSpacesUseCase
{
    protected $spaceService;

    public function __construct(SpaceService $spaceService)
    {
        $this->spaceService = $spaceService;
    }

    public function execute()
    {
        return $this->spaceService->getAllSpaces();
    }
}