<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Space;
use Tymon\JWTAuth\Facades\JWTAuth;
class SpaceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token = JWTAuth::fromUser($this->user);
    }

    public function test_user_can_create_space()
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->postJson('/api/spaces', [
            'name' => 'New Space',
            'description' => 'A new space for testing',
            'capacity' => 30,
            'type' => 'room',
        ]);

        
        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'New Space',
                'description' => 'A new space for testing',
                'capacity' => 30,
                'type' => 'room',
            ]);
    }

    public function test_user_can_update_space()
    {
        $space = Space::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->putJson("/api/spaces/{$space->id}", [
            'name' => 'Updated Space',
            'description' => 'An updated space description',
            'capacity' => 40,
            'type' => 'meeting_room',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Updated Space',
                'description' => 'An updated space description',
                'capacity' => 40,
                'type' => 'meeting_room',
            ]);
    }

    public function test_user_can_delete_space()
    {
        $space = Space::factory()->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->deleteJson("/api/spaces/{$space->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('spaces', ['id' => $space->id]);
    }

    public function test_user_can_list_spaces()
    {
        Space::factory()->count(3)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)->getJson('/api/spaces');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }
}