<?php

namespace Tests\Factories\Traits;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Arr;

trait CreatesUser
{
    private function createUser(array $options = []): User
    {
		$user = User::factory()->create();
        $userFactory = User::factory();
        foreach (Arr::get($options, 'states.user', []) as $state) {
            $userFactory->$state();
        }
        $userFactory->create(Arr::get($options, 'attributes.user', []));
        $userProfileFactory = UserProfile::factory();
        foreach (Arr::get($options, 'states.user_profile', []) as $state) {
            $userProfileFactory->$state();
        }
        $user->profile()->save(
                $userProfileFactory->make(Arr::get($options, 'attributes.user_profile', []))
        );

		return $user;
    }
}