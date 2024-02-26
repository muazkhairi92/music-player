<?php

namespace Modules\Subscription\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Subscription\App\Models\Transaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'subscription_id' => null,
            'transaction_id' => Str::random(10),
            'eghl_transaction_status' => 2,
            'amount' => 100,
        ];
    }
}

