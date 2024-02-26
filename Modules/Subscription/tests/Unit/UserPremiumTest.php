<?php

namespace Modules\Subscription\tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Modules\Subscription\App\Enums\EghlTransactionStatusType;
use Modules\Subscription\App\Enums\UserPlan;
use Modules\Subscription\App\Models\Subscription;
use Modules\Subscription\App\Models\Transaction;
use Modules\Subscription\tests\SubscriptionTestCase;

class UserPremiumTest extends SubscriptionTestCase
{
    use DatabaseTransactions;

    /**
     * A basic unit test example.
     *
     * @return void
     */

    /** @test */
    public function userMakePayment()
    {
        $user = $this->createUser();
        $subscription = $this->createSubscription($user->id);

        $transaction = Transaction::create(['subscription_id' => $subscription->id,
        'transaction_id' => Str::random(10),
        'eghl_transaction_status' => EghlTransactionStatusType::Pending,
        'amount' => 100, ]);
        
        $payload = [
            'HashValue' => 'foobar',
            'HashValue2' => 'foobar',
            'IssuingBank' => 'eGHL Test',
            'OrderNumber' => $transaction->transaction_id,
            'PaymentID' => $transaction->transaction_id,
            'PymtMethod' => 'CC',
            'TransactionType' => 'SALE',
            'TxnID' => $transaction->transaction_id,
            'TxnMessage' => 'Transaction Successful',
            'TxnStatus' => 0,
            'Amount' => '100.00',
            'CurrencyCode' => 'MYR',
            'AuthCode' => 'bla',
            'Param6' => '1231231',
            'Param7' => '1231231',
        ];
        $string = config('services.eghl.password').$payload['TxnID'].config('services.eghl.service_id').$payload['PaymentID'].$payload['TxnStatus'].$payload['Amount'].$payload['CurrencyCode'].$payload['AuthCode'].$payload['OrderNumber'].$payload['Param6'].$payload['Param7'];

        $hash = hash('sha256', $string);

        $payload['HashValue2'] = $hash;

        $res = $this
                ->post(
                    'api/callback',
                    $payload
                );
        $res->assertStatus(200);

        $this->assertDatabaseHas(Transaction::class, [
            'subscription_id' => $subscription->id,
            'amount' => 100,
            'eghl_transaction_status' => EghlTransactionStatusType::Success,
        ]);
        $this->assertDatabaseHas(Subscription::class, [
            'user_id' => $user->id,
            'user_plan' => UserPlan::Premium,
            'paid' => true,
        ]);
    }

    /** @test */
    public function userMakePaymentFailed()
    {
        $user = $this->createUser();
        $subscription = $this->createSubscription($user->id);

        $transaction = Transaction::create(['subscription_id' => $subscription->id,
        'transaction_id' => Str::random(10),
        'eghl_transaction_status' => EghlTransactionStatusType::Pending,
        'amount' => 100, ]);

        $payload = [
            'HashValue' => 'foobar',
            'HashValue2' => 'foobar',
            'IssuingBank' => 'eGHL Test',
            'OrderNumber' => $transaction->transaction_id,
            'PaymentID' => $transaction->transaction_id,
            'PymtMethod' => 'CC',
            'TransactionType' => 'SALE',
            'TxnID' => $transaction->transaction_id,
            'TxnMessage' => 'Transaction Successful',
            'TxnStatus' => 1,
            'Amount' => '100.00',
            'CurrencyCode' => 'MYR',
            'AuthCode' => 'bla',
            'Param6' => '1231231',
            'Param7' => '1231231',
        ];
        $string = config('services.eghl.password').$payload['TxnID'].config('services.eghl.service_id').$payload['PaymentID'].$payload['TxnStatus'].$payload['Amount'].$payload['CurrencyCode'].$payload['AuthCode'].$payload['OrderNumber'].$payload['Param6'].$payload['Param7'];

        $hash = hash('sha256', $string);

        $payload['HashValue2'] = $hash;

        $res = $this
                ->post(
                    'api/callback',
                    $payload
                );
        $res->assertStatus(200);

        $this->assertDatabaseMissing(Transaction::class, [
            'subscription_id' => $subscription->id,
            'amount' => 100,
            'eghl_transaction_status' => EghlTransactionStatusType::Success,
        ]);
        $this->assertDatabaseMissing(Subscription::class, [
            'user_id' => $user->id,
            'user_plan' => UserPlan::Premium,
            'paid' => true,
        ]);
    }

    /** @test */
    public function userAutoUpdateExpired()
    {
        $user = $this->createUser();

        Carbon::setTestNow(Carbon::create('2022', '12', '15'));

        Subscription::create([
            'user_id' => $user->id,
            'end_date' => '2022-12-14',
            'paid' => true,
            'user_plan' => UserPlan::Premium,
        ]);
        $this->artisan('subscription:update-user-plan');
        $this->assertDatabaseHas('subscriptions', [
            'end_date' => '2022-12-14',
            'paid' => false,
            'user_plan' => UserPlan::Expired,
        ]);
    }

    /** @test */
    public function userNotUpdateExpired()
    {
        $user = $this->createUser();

        Carbon::setTestNow(Carbon::create('2022', '12', '13'));

        Subscription::create([
            'user_id' => $user->id,
            'end_date' => '2022-12-14',
            'paid' => true,
            'user_plan' => UserPlan::Premium,
        ]);
        $this->artisan('subscription:update-user-plan');
        $this->assertDatabaseMissing('subscriptions', [
            'end_date' => '2022-12-14',
            'paid' => false,
            'user_plan' => UserPlan::Expired,
        ]);
    }
}
