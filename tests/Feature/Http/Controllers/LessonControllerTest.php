<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Lesson;

class LessonControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testShow()
    {
        $lesson = Lesson::factory()->create(['name' => '楽しいヨガレッスン']);

        $response = $this->get("/lessons/{$lesson->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($lesson->name);
        $response->assertSee('空き状況: ×');
    }
}
