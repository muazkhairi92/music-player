<?php

namespace Modules\Subscription\App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Subscription\App\Enums\UserPlan;
use Modules\Subscription\App\Models\Subscription;

class UpdateUserPlanJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // update all expired job advertisement to inactive status
        Subscription::where('end_date', '<=', Carbon::now())
            ->where('paid', true)
            ->chunkById(
                1000,
                function ($subscriptions) {
                    $subscriptions->each(function ($subscription) {
                        $subscription->update(['user_plan' => UserPlan::Expired,'paid'=>false]);
                    });
                }
            );  
    }
}
