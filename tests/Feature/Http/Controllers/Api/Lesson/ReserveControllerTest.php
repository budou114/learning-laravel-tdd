<?php

namespace Tests\Feature\Http\Controllers\Api\Lesson;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Lesson;
use Tests\Factories\Traits\CreatesUser;

class ReserveControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUser;

    public function testInvoke_正常系()
    {
        $lesson = Lesson::factory()->create();
        $user = $this->createUser();
        $this->actingAs($user, 'api');

        $response = $this->postJson("/api/lessons/{$lesson->id}/reserve");
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'lesson_id' => $lesson->id,
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('reservations', [
            'lesson_id' => $lesson->id,
            'user_id' => $user->id,
        ]);
    }
}
