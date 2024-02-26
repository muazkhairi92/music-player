<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\Subscription\App\Enums\UserPlan;
use Modules\Subscription\App\Models\Subscription;
use Modules\User\App\Models\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createUser(array $userData = [])
    {
        return User::create([
            'name' => $userData['name'] ?? 'admin',
            'email' => $userData['email'] ?? 'admin2@email.com',
            'password' => bcrypt('password'),
            'user_status' => 1,
          ]);
    }

    public function createNormalSubscription(int $userId)
    {
        return Subscription::create([
            'user_id' => $userId,
            'user_plan' => UserPlan::Normal,
            'paid' => false,
          ]);
    }

    public function createPremiumSubscription(int $userId)
    {
        return Subscription::create([
            'user_id' => $userId,
            'user_plan' => UserPlan::Premium,
            'paid' => true,
          ]);
    }
}
