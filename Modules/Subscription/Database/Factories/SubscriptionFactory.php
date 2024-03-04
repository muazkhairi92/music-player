<?php

namespace Modules\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Subscription\App\Enums\UserPlan;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Subscription\App\Models\Subscription::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id'=>null,
            'user_plan'=> UserPlan::Normal,
            'paid'=>false,
        ];
    }
}

