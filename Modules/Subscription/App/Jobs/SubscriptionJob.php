<?php

namespace Modules\Subscription\App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Subscription\App\Enums\UserPlan;
use Modules\Subscription\App\Enums\UserTransactionType;
use Modules\Subscription\App\Models\Transaction;

class SubscriptionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $subscription;

    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subscription, $transaction)
    {
        $this->subscription = $subscription;
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //check if the transaction status is successful
        if ($this->transaction->eghl_transaction_status == 0) {
            //check if the transaction is still pending on our side
            if ($this->transaction->user_transaction_status == UserTransactionType::Pending) {
                DB::transaction(function () {

                    $this->subscription->update([
                        'paid'=> true,
                        'user_plan' => UserPlan::Premium,
                        'start_date' => Carbon::now(),
                        'end_date' => Carbon::now()->addYear(),

                    ]);

                    //update transaction status
                    $this->transaction->update([
                        'user_transaction_status' => UserTransactionType::Success,
                    ]);

                });
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        // 
    }
}
