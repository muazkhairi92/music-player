<?php

namespace Modules\Subscription\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Subscription\App\Http\Resources\SubscriptionHomeResource;
use Modules\Subscription\App\Http\Resources\TransactionIndexResource;
use Modules\Subscription\App\Models\Transaction;

class SubscriptionController extends Controller
{
    /**
     * user's subscription home.
     */
    public function home()
    {
        $subscription = Auth::user()->subscription;

        return $this->sendResponse(
            'Subscription home page.',
            new SubscriptionHomeResource($subscription->load('user')),
            200
        );
    }

    /**
     * user's transactions list.
     */
    public function index()
    {
        $subscription = Auth::user()->subscription;
        $transactions = Transaction::where([['subsription_id', $subscription->id], ['type', request('type')]])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return TransactionIndexResource::collection($transactions);
    }
}
