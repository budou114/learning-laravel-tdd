<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Lesson;
use App\Models\User;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'lesson_id' => function () {
                return Lesson::factory()->create()->id;
            },
            'user_id' => function () {
                return User::factory()->create()->id;
            },
        ];
    }
}
