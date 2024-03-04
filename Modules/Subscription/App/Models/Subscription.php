<?php

namespace Modules\Subscription\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Profile\App\Models\Profile;
use Modules\Subscription\App\Enums\UserPlan;
use Modules\Subscription\Database\factories\SubscriptionFactory;
use Modules\User\App\Models\User;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'user_plan',
        'paid',
        'start_date',
        'end_date'
    ];

    protected static function newFactory(): SubscriptionFactory
    {
        return SubscriptionFactory::new();
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Transaction()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getisPremiumAttribute()
    {
        return $this->user_plan === UserPlan::Premium;
    }
}
