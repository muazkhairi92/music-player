<?php

namespace Modules\Playlist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Playlist\App\Models\PlayListList;

class PlaylistFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Playlist\App\Models\Playlist::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(4, true),
            'list' => PlayListList::factory(4)->raw(),
            'user_id' => null];
    }
}

