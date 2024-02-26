<?php

namespace Modules\Subscription\App\Services;

use App\Jobs\PortfolioDepositJob;
use App\Jobs\UpdateReferrerJob;
use App\Models\Share;
use App\Models\Transaction;
use Modules\Subscription\App\Enums\UserTransactionType;
use Modules\Subscription\App\Jobs\SubscriptionJob;
use Modules\Subscription\App\Jobs\UpdateTrasactionStatus;

class TransactionService
{

    public function updateTransaction(object $transaction, object $data)
    {
        // update data based on eghl status (success or fail)
        $newRemarks = $data['TxnStatus'] == 0 ? 'success' : 'failed';

        $updateData = [
            'payment_method' => $data['PymtMethod'] ?? null,
            'gateway_transaction_id' => $data['TxnID'] ?? null,
            'eghl_transaction_status' => $data['TxnStatus'] ?? null,
            'remarks' => $data['TxnMessage'] ?? $newRemarks,
            'user_transaction_status' => $data['TxnStatus'] == 0 ? UserTransactionType::Pending : UserTransactionType::Fail,
        ];
        $transaction->update($updateData);

        //if eghl is a success
        if ($data['TxnStatus'] == 0) {
            dispatch(new SubscriptionJob($transaction->subscription, $transaction));

        }

        dispatch(new UpdateTrasactionStatus());

        return $data['TxnStatus'] == 0;
    }
}
