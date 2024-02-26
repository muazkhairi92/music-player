<?php

namespace Modules\Playlist\tests;

use Modules\Playlist\App\Models\Playlist;
use Tests\CreatesApplication;
use Tests\TestCase;
use Illuminate\Support\Str;
use Modules\Playlist\App\Models\PlayListList;

abstract class PlaylistTestCase extends TestCase
{
    use CreatesApplication;

    public function createPlaylist($userId,array $data = null){
        $playlist = Playlist::create([
            'user_id' =>$userId,
            'name' => Str::random(10)
        ]);
        if($data){
            foreach ($data as $list){
                PlayListList::create([
                    'playlist_id' => $playlist->id,
                    'song_id' => $list->song_id,
                    'order_number'=> $list->order_number,
                ]);  
            }
        }
        else{
            PlayListList::create([
                'playlist_id' => $playlist->id,
                'song_id' => 1,
                'order_number'=> 1,
            ]);
            PlayListList::create([
                'playlist_id' => $playlist->id,
                'song_id' => 2,
                'order_number'=> 2,
            ]);
        }
        $playlist->refresh();
        return $playlist;
    }
}
