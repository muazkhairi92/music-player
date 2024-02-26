<?php

namespace Modules\User\tests\Unit;

use Carbon\Carbon;
use Faker\Provider\UserAgent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\tests\UserTestCase;

class UserTest extends UserTestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
    }
     /** @test */

     public function can_view_all_users(): void
     {
        $user = $this->createUser();
        $user2 = $this->createUser(['name'=>'abu ali','email'=>'test123@email.com']);
 
         $this->actingAs($user, 'sanctum')->getJson("/api/v1/users")
         ->assertOk()
         ->assertJson([
             "success" =>true,
             "data"=> [
                 "data" => [
                     [
                         "id"=> $user2->id,
                         "name"=> $user2->name,
                     ],
                 ],
             ],
         ]);
     }

     /** @test */
     public function can_delete_user(): void
     {
        $user = $this->createUser();
 
         $this->actingAs($user, 'sanctum')->deleteJson("/api/v1/user")
         ->assertOk();
         $this->assertDatabaseHas('users',['id'=>$user->id,'deleted_at'=>Carbon::now()]);
      
     }

     /** @test */
     public function can_edit_user(): void
     {
        $user = $this->createUser();
 
         $this->actingAs($user, 'sanctum')->putJson("/api/v1/user",['old_password'=>'password','name'=>'abu bakar'])
         ->assertOk();
         $this->assertDatabaseHas('users',['id'=>$user->id,'name'=>'abu bakar']);
      
     }

     /** @test */
     public function edit_user_wrong_password(): void
     {
        $user = $this->createUser();
 
         $this->actingAs($user, 'sanctum')->putJson("/api/v1/user",['old_password'=>'password12','name'=>'abu bakar'])
         ->assertStatus(400)
         ->assertJson(['success'=>false,'message'=>'Failed too update user']);
         $this->assertDatabaseMissing('users',['id'=>$user->id,'name'=>'abu bakar']);
      
     }
}