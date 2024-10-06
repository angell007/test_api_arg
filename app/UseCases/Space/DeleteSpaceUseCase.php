<?php

namespace App\UseCases\Space;

use App\Services\SpaceService;

class DeleteSpaceUseCase
{
    protected $spaceService;

    public function __construct(SpaceService $spaceService)
    {
        $this->spaceService = $spaceService;
    }

    public function execute($id)
    {
        $this->spaceService->deleteSpace($id);
    }
}