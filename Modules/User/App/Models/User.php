<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Playlist\App\Models\Playlist;
use Modules\Subscription\App\Models\Subscription;
use Modules\User\Database\factories\UserFactory;

class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): UserFactory
    {
        // return UserFactory::new();
    }

    public function Playlist()
    {
        return $this->hasMany(Playlist::class);
    }

    public function Subscription()
    {
        return $this->hasOne(Subscription::class);
    }
}
