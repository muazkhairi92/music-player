<?php

namespace Modules\User\tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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

    public function can_register(): void
    {
 
        $payLoad = [
            'email' => 'user@email.com',
            'name' => 'testUser',
            'password'=>'password',
            'password_confirmation'=>'password',

        ];

        $this->postJson('/api/register',$payLoad)
        ->assertOk();

        $this->assertDatabaseHas('users',[
            'name'=>$payLoad['name'],
            'email'=>$payLoad['email'],
        ]);

    }

    /** @test */

    public function login_success(): void
    {
        $this->createUser();

        $payLoad = [
            'email' => 'admin2@email.com',
            'password' =>'password'
        ];
        $this->postJson('/api/login',$payLoad)
        ->assertOk();
    }
    
    /** @test */

    public function login_failed(): void
    {
        $this->createUser();

        $payLoad = [
            'email' => 'admin2@email.com',
            'password' =>'password123'
        ];
        $this->postJson('/api/login',$payLoad)
        ->assertStatus(400)
        ->assertJson(["success" => false,
        "message" => "Authentication Failed"]);
    }

    /** @test */

    public function login_too_many_attempts(): void
    {
        $this->createUser();

        $payLoad = [
            'email' => 'admin2@email.com',
            'password' =>'password123'
        ];
        $this->postJson('/api/login',$payLoad);
        $this->postJson('/api/login',$payLoad);
        $this->postJson('/api/login',$payLoad);
        $this->postJson('/api/login',$payLoad);
        $this->postJson('/api/login',$payLoad)
        ->assertStatus(400)
        ->assertJson(["success" => false,
        "message" => "Too many login attempts"]);
    }


}
