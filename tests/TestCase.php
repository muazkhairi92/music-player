<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
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
}
