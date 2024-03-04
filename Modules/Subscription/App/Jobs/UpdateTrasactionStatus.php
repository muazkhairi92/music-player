<?php

namespace Modules\Subscription\App\Jobs;


use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Subscription\App\Enums\EghlTransactionStatusType;
use Modules\Subscription\App\Enums\UserTransactionType;
use Modules\Subscription\App\Models\Transaction;

class UpdateTrasactionStatus implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $updateData = [
            'eghl_transaction_status' => 1,
            'remarks' => 'User Time Out',
            'user_transaction_status' => UserTransactionType::Fail,
        ];

        //update all transaction to fail
        Transaction::where([
            ['eghl_transaction_status', EghlTransactionStatusType::Pending],
            ['created_at', '<', Carbon::now()->subMinutes(15)],
        ])->get()->each(function ($transaction) use ($updateData) {
            $transaction->update($updateData);
        });
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        // 
    }
}
