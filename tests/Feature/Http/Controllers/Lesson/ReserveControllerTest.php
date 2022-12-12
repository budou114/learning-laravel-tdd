<?php

namespace Tests\Feature\Http\Controllers\Lesson;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\Factories\Traits\CreatesUser;
use App\Models\Lesson;
use App\Models\Reservation;

class ReserveControllerTest extends TestCase
{
    use RefreshDatabase;
    use CreatesUser;

    public function testInvoke_正常系()
    {
        $lesson = Lesson::factory()->create();
        $user = $this->createUser();
        $this->actingAs($user);

        $response = $this->post("/lessons/{$lesson->id}/reserve");

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect("/lessons/{$lesson->id}");
        // TODO データベースのアサーション
    }

    public function testInvoke_異常系()
    {
        $lesson = Lesson::factory()->create(['capacity' => 1]);
        $anotherUser = $this->createUser();
        $lesson->reservations()->save(Reservation::factory()->make(['user_id' => $anotherUser->id]));

        $user = $this->createUser();
        $this->actingAs($user);

        $response = $this->from("/lessons/{$lesson->id}")
            ->post("/lessons/{$lesson->id}/reserve");

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect("/lessons/{$lesson->id}");
        $response->assertSessionHasErrors();
        // メッセージの中身まで確認したい場合は以下の2行も追加
        $error = session('errors')->first();
        $this->assertStringContainsString('予約できません。', $error);

        $this->assertDatabaseMissing('reservations', [
            'lesson_id' => $lesson->id,
            'user_id' => $user->id,
        ]);
    }
}
