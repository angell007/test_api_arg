<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'space_id' => Space::factory(),
            'start_time' => Carbon::now()->addHours(rand(1, 10)),
            'end_time' => Carbon::now()->addHours(rand(11, 20)),
            'description' => $this->faker->sentence,
        ];
    }
}