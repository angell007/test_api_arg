<?php

namespace Database\Factories;

use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    protected $model = Space::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word . ' Space',
            'description' => $this->faker->sentence,
            'capacity' => $this->faker->numberBetween(5, 100),
            'type' => $this->faker->randomElement(['room', 'desk', 'meeting_room']),
        ];
    }
}