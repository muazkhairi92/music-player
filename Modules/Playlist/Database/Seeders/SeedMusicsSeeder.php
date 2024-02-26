<?php

namespace Modules\Playlist\Database\Seeders;

use File;
use Illuminate\Database\Seeder;
use Modules\Playlist\App\Models\Song;

class SeedMusicsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Song::truncate();

        $json = File::get('modules/playlist/database/data/songs.json');
        $banks = json_decode($json);

        foreach ($banks as $key => $value) {
            Song::create([
                'title' => $value->title,
                'artist' => $value->artist,
                'artwork' => $value->artwork,
                'url' => $value->url,
            ]);
        }
    }
}
