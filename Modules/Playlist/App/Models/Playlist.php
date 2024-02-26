<?php

namespace Modules\Playlist\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Playlist\Database\factories\PlaylistFactory;
use Modules\User\App\Models\User;

class Playlist extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    protected static function newFactory(): PlaylistFactory
    {
        // return PlaylistFactory::new();
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
