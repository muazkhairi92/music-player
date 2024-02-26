<?php

namespace Modules\Playlist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SongFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Playlist\App\Models\Song::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

