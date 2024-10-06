<?php

namespace App\UseCases\Space;

use App\Services\SpaceService;
use Illuminate\Support\Facades\Log;

class CreateSpaceUseCase
{
    protected $spaceService;

    public function __construct(SpaceService $spaceService)
    {
        $this->spaceService = $spaceService;
    }

    public function execute(array $data)
    {
        Log::info($data);
        return $this->spaceService->createSpace($data);
    }
}