<?php

namespace Modules\Playlist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlaylistListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Playlist\App\Models\PlaylistList::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'playlist_id' => null,
            'song_id'=> Str::rand(1,6),
            'order_number' => null,
        ];
    }
}

