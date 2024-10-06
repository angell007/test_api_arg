<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Space;
use App\Models\Reservation;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $space;
    protected $token;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->space = Space::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }


    public function test_user_can_create_reservation()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson('/api/reservations', [
            'space_id' => $this->space->id,
            'start_time' => Carbon::now()->addHour(),
            'end_time' => Carbon::now()->addHours(2),
            'description' => 'Test reservation',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'space_id' => $this->space->id,
                'user_id' => $this->user->id,
            ]);
    }

    public function test_user_can_update_reservation()
    {
        $reservation = Reservation::factory()->create([
            'user_id' => $this->user->id,
            'space_id' => $this->space->id,
        ]);

        $newStartTime = Carbon::now()->addHours(3);
        $newEndTime = Carbon::now()->addHours(4);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->putJson("/api/reservations/{$reservation->id}", [
            'space_id' => $this->space->id,
            'start_time' => $newStartTime,
            'end_time' => $newEndTime,
            'description' => 'Updated reservation',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'space_id' => $this->space->id,
            'start_time' => $newStartTime->toDateTimeString(),
            'end_time' => $newEndTime->toDateTimeString(),
            'description' => 'Updated reservation',
            ]);
    }

    public function test_user_can_delete_reservation()
    {
        $reservation = Reservation::factory()->create([
            'user_id' => $this->user->id,
            'space_id' => $this->space->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->deleteJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    public function test_user_can_list_own_reservations()
    {
        Reservation::factory()->count(3)->create([
            'user_id' => $this->user->id,
            'space_id' => $this->space->id,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->getJson('/api/reservations');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_cannot_create_overlapping_reservation()
    {
        Reservation::factory()->create([
            'user_id' => $this->user->id,
            'space_id' => $this->space->id,
            'start_time' => Carbon::now()->addHour(),
            'end_time' => Carbon::now()->addHours(2),
            'description' => 'Test reservation',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson('/api/reservations', [
            'space_id' => $this->space->id,
            'start_time' => Carbon::now()->addMinutes(90),
            'end_time' => Carbon::now()->addHours(3),
            'description' => 'Test reservation',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['overlap']);
    }
}
