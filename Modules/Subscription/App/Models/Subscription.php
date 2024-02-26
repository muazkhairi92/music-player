<?php

namespace Modules\Subscription\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Subscription\Database\factories\SubscriptionFactory;
use Modules\User\App\Models\User;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): SubscriptionFactory
    {
        //return SubscriptionFactory::new();
    }
    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
