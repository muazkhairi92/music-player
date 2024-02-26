<?php

namespace Modules\Subscription\App\Http\Controllers;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Subscription\App\Enums\EghlTransactionStatusType;
use Modules\Subscription\App\Http\Requests\TransactionRequest;
use Modules\Subscription\App\Models\Transaction;
use Modules\Subscription\App\Services\Eghl;
use Modules\Subscription\App\Services\TransactionService;

class TransactionController extends Controller
{
    protected $TransactionService;

    public function __construct(TransactionService $TransactionService)
    {
        $this->TransactionService = $TransactionService;
    }


    /**
     * Generate eghl payment url
     */
    public function generatePaymentUrl(TransactionRequest $request)
    {
        $user = Auth::user();


        // Create transaction record with status pending
        $transactionId = ($transaction = Transaction::latest()->first()) ?
            $newId = $transaction->id.Carbon::now()->format('Hi') :
            $newId = Carbon::now()->format('Hi');

        //data to pass to generate eghl link
        $data = [
            'PaymentID' => $newId,
            'OrderNumber' => $transactionId,
            'PaymentDesc' => $request->type,
            'Amount' => number_format($request->total_amount, 2, '.', ''),
            'CustName' => $user->name,
            'CustEmail' => $user->profile->email,
            'CustPhone' => $user->mobile_number,
            'PymtMethod' => $request->payment_method,
        ];

        $paymentUrl = (new Eghl())->processPaymentRequest($data);


        // store data with pending status
        Transaction::create([
            'portfolio_id' => $user->portfolio->id,
            'eghl_transaction_status' => EghlTransactionStatusType::Pending,
            'transaction_id' => $newId,
            'type' => $request->type,

            //reason being transaction table would only track the shares amount, not amount with add-on fees
            'amount' => $request->shares,
            'invoice_no' => $newId,
        ]);

        return $this->sendResponse(
            'Payment redirect url',
            ['payment_url' => $paymentUrl],
            200
        );
    }

    /**
     * Callback response from eghl server
     * @param Request $data
     * @return Response|ResponseFactory|void
     */
    public function callback(Request $data)
    {
        $validated = (new Eghl())->validatePaymentResponse($data);
        $transaction = Transaction::firstWhere('transaction_id', $data['OrderNumber']);

        if ($validated && $transaction->eghl_transaction_status == EghlTransactionStatusType::Pending) {
            $this->TransactionService->updateTransaction($transaction, $data);

            return response('OK', 200);
        }
    }

    /**
     *Redirect response from eghl server
     * Refer eGHL-API-Ver 2.9q.pdf section 5.2 Payment/Capture Transaction Status
     * Success = 0
     * Fail = 1
     * Pending = 2
     */
    public function redirect(Request $data)
    {
        $validated = (new Eghl())->validatePaymentResponse($data);
        $transaction = Transaction::firstWhere('transaction_id', $data['OrderNumber']);

        if ($validated && $transaction->eghl_transaction_status == EghlTransactionStatusType::Pending) {
            $this->TransactionService->updateTransaction($transaction, $data);
        }

        $transaction->eghl_transaction_status == 0 ? $status = 'successful' : $status = 'not successful';

        return view('welcome', ['status' => $status]);
    }

}
