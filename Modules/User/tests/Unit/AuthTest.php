<?php

namespace Modules\User\tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Notification;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;
use Modules\User\tests\UserTestCase;

class AuthTest extends UserTestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        Notification::fake();
    }

    /** @test */
    public function canRegister(): void
    {
        $payLoad = [
            'email' => 'user@email.com',
            'name' => 'testUser',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->postJson('/api/register', $payLoad)
        ->assertOk();

        $this->assertDatabaseHas('users', [
            'name' => $payLoad['name'],
            'email' => $payLoad['email'],
        ]);
    }

    /** @test */
    public function loginSuccess(): void
    {
        $this->createUser();

        $payLoad = [
            'email' => 'admin2@email.com',
            'password' => 'password',
        ];
        $this->postJson('/api/login', $payLoad)
        ->assertOk();
    }

    /** @test */
    public function loginFailed(): void
    {
        $this->createUser();

        $payLoad = [
            'email' => 'admin2@email.com',
            'password' => 'password123',
        ];
        $this->postJson('/api/login', $payLoad)
        ->assertStatus(400)
        ->assertJson(['success' => false,
        'message' => 'Authentication Failed']);
    }

    /** @test */
    public function loginTooManyAttempts(): void
    {
        $this->createUser();

        $payLoad = [
            'email' => 'admin2@email.com',
            'password' => 'password123',
        ];
        $this->postJson('/api/login', $payLoad);
        $this->postJson('/api/login', $payLoad);
        $this->postJson('/api/login', $payLoad);
        $this->postJson('/api/login', $payLoad);
        $this->postJson('/api/login', $payLoad)
        ->assertStatus(400)
        ->assertJson(['success' => false,
        'message' => 'Too many login attempts']);
    }

    /** @test */
    public function loginSSOGoogle(): void
    {
        $user = $this->mock('Laravel\Socialite\Contracts\User', function (MockInterface $mock) {
            $mock->id = '12345';
            $mock->name = 'Chris Willerton';
            $mock->email = 'hello@chriswillerton.com';
            $mock->token = '123456789abcdef';
            $mock->refreshToken = '123456789abcdef';
        });

        $provider = $this->mock('Laravel\Socialite\Contracts\Provider', function (MockInterface $mock) use ($user) {
            $mock
                ->shouldReceive('user')
                ->andReturn($user);
            $mock->shouldReceive('redirect')->andReturn(redirect('/auth/google/callback')); // Set up an expectation for the redirect method
        });

        Socialite::shouldReceive('driver')
        ->with('google')
        ->andReturn($provider);

        // Perform the SSO login
        $response = $this->get('/auth/google'); // Replace with the actual route for your SSO login

        // Add assertions based on the expected behavior of your SSO login
        $response->assertRedirect('/auth/google/callback'); // Replace with the expected redirect after successful login
        $response->assertStatus(302);
        $response = $this->followRedirects($response);
        $response->assertStatus(200); // Replace with the expected status code
        $this->assertDatabaseHas('users', [
            'name' => 'Chris Willerton',
            'email' => 'hello@chriswillerton.com',
        ]);
    }
}
