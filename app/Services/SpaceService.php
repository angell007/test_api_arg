<?php

namespace App\Services;

use App\Repositories\SpaceRepository;

class SpaceService
{
    protected $spaceRepository;

    public function __construct(SpaceRepository $spaceRepository)
    {
        $this->spaceRepository = $spaceRepository;
    }

    public function getAllSpaces()
    {
        return $this->spaceRepository->getAll();
    }

    public function getSpace($id)
    {
        return $this->spaceRepository->findById($id);
    }

    public function createSpace(array $data)
    {
        return $this->spaceRepository->create($data);
    }

    public function updateSpace($id, array $data)
    {
        $space = $this->spaceRepository->findById($id);
        return $this->spaceRepository->update($space, $data);
    }

    public function deleteSpace($id)
    {
        $space = $this->spaceRepository->findById($id);
        $this->spaceRepository->delete($space);
    }
}