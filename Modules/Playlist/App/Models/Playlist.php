<?php

namespace Modules\Playlist\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Playlist\Database\factories\PlaylistFactory;

class Playlist extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];
    
    protected static function newFactory(): PlaylistFactory
    {
        //return PlaylistFactory::new();
    }
}
