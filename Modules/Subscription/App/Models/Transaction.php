<?php

namespace Modules\Subscription\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Subscription\Database\factories\TransactionFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'portfolio_id',
        'eghl_transaction_status',
        'user_transaction_status',
        'gateway_transaction_id',
        'amount',
        'payment_method',
        'transaction_id',
        'type',
        'invoice_no',
        'remarks',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
    
    protected static function newFactory(): TransactionFactory
    {
        return TransactionFactory::new();
    }
}
