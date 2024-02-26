<?php

namespace Modules\Playlist\tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Playlist\App\Models\Playlist;
use Modules\Playlist\App\Models\PlayListList;
use Modules\Playlist\tests\PlaylistTestCase;

class PlaylistTest extends PlaylistTestCase
{
    use DatabaseTransactions;
    /**
     * A basic unit test example.
     *
     * @return void
     */

     /** @test */
    public function can_create_playlist()
    {
        $user = $this->createUser();
        $this->createPremiumSubscription($user->id);
        $payload = [
            'name'=> 'test playlist',
            'list' =>
            [
                [
                    'song_id'=> 1,
                    'order_number'=> 2,
                ],
                [
                    'song_id'=> 2,
                    'order_number'=> 1,
                ]
            ],
        ];

        $this->actingAs($user, 'sanctum')->postJson("/api/v1/playlist",$payload)
        ->assertOk();

        $this->assertDatabaseHas(Playlist::class, [
            'user_id' => $user->id,
            'name' => $payload['name'],
        ]);
        $playlistId = Playlist::firstWhere('name',$payload['name'])->pluck('id');
        $this->assertDatabaseHas(PlayListList::class, [
            'playlist_id' => $playlistId,
            'song_id' => $payload['list'][0]['song_id'],
            'order_number' => $payload['list'][0]['order_number'],
        ]);

    }
     /** @test */
    public function cannot_create_playlist_not_premium()
    {
        $user = $this->createUser();
        $this->createNormalSubscription($user->id);
        $payload = [
            'name'=> 'test playlist',
            'list' =>
            [
                [
                    'song_id'=> 1,
                    'order_number'=> 2,
                ],
                [
                    'song_id'=> 2,
                    'order_number'=> 1,
                ]
            ],
        ];

        $this->actingAs($user, 'sanctum')->postJson("/api/v1/playlist",$payload)
        ->assertStatus(403);

        $this->assertDatabaseMissing(Playlist::class, [
            'user_id' => $user->id,
            'name' => $payload['name'],
        ]);
    }
}
