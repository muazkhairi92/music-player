<?php

namespace Modules\Subscription\tests;

use Modules\Subscription\App\Enums\UserPlan;
use Modules\Subscription\App\Models\Subscription;
use Modules\User\App\Enums\UserStatus;
use Modules\User\App\Models\User;
use Tests\CreatesApplication;
use Tests\TestCase;

abstract class SubscriptionTestCase extends TestCase
{
    use CreatesApplication;

    public function createSubscription(int $userId, object $data =null){
        return Subscription::create([
            'user_id'=>$userId,
            'user_plan'=> $data->user_plan ?? UserPlan::Normal,
            'paid'=>$data->paid??false,
          ]);
    }
}
