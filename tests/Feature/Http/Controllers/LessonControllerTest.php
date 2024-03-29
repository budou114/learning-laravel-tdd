<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use App\Models\Lesson;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserProfile;
use Tests\Factories\Traits\CreatesUser;


class LessonControllerTest extends TestCase
{
    use RefreshDatabase, CreatesUser;

    /**
     * @param int $capacity
     * @param int $reservationCount
     * @param string $expectedVacancyLevelMark
     * @param string $button
     * @dataProvider dataShow
     */
    public function testShow(int $capacity, int $reservationCount, string $expectedVacancyLevelMark, string $button)
    {
        $lesson = Lesson::factory()->create(['name' => '楽しいヨガレッスン', 'capacity' => $capacity]);
        for ($i = 0; $i < $reservationCount; $i++) {
            $user = User::factory()->create();
            UserProfile::factory()->create(['user_id' => $user->id]);
            // 状態やプロパティを指定したい場合
            // テストケースによって変化するならデータプロバイダから渡すといいでしょう
            $options = [
                'states' => [
                    'user' => ['加入1年未満'],
                    'user_profile' => ['ゴールド会員', 'シニア会員'],
                ],
                'attributes' => [
                    // 決まった名前が必要なケースはないと思いますが、あくまで例として
                    'user' => ['name' => '山田太郎'],
                    'user_profile' => ['plan' => 'gold'],
                ],
            ];
            $user = $this->createUser();

            Reservation::factory()->create(['lesson_id' => $lesson->id, 'user_id' => $user->id]);
        }

        $user = User::factory()->create();
        UserProfile::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);

        $response = $this->get("/lessons/{$lesson->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee($lesson->name);
        $response->assertSee("空き状況: {$expectedVacancyLevelMark}");

        $response->assertSee($button, false);
    }

    public function dataShow()
    {
        $button = '<button class="btn btn-primary">このレッスンを予約する</button>';
        $span = '<span class="btn btn-primary disabled">予約できません</span>';

        return [
            '空き十分' => [
                'capacity' => 6,
                'reservationCount' => 1,
                'expectedVacancyLevelMark' => '◎',
                'button' => $button,
            ],
            '空きわずか' => [
                'capacity' => 6,
                'reservationCount' => 2,
                'expectedVacancyLevelMark' => '△',
                'button' => $button,
            ],
            '空きなし' => [
                'capacity' => 1,
                'reservationCount' => 1,
                'expectedVacancyLevelMark' => '×',
                'button' => $span,
            ],
        ];
    }
}
