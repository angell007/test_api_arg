<?php

namespace App\UseCases\Space;

use App\Models\Space;

class GetSpaceUseCase
{
    public function execute($id)
    {
        return Space::find($id);
    }
}
