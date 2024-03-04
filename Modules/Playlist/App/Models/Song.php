<?php

namespace Modules\Playlist\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Playlist\Database\factories\SongFactory;

class Song extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'artist',
        'artwork',
        'url'
    ];
    
    protected static function newFactory(): SongFactory
    {
        return SongFactory::new();
    }

    public function PlaylistList()
    {
        return $this->hasMany(PlayListList::class);

    }
}
