<?php

namespace Modules\Playlist\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Playlist\Database\factories\PlayListListFactory;

class PlayListList extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'playlist_id',
        'song_id',
        'order_number'
    ];
    
    protected static function newFactory(): PlayListListFactory
    {
        return PlayListListFactory::new();
    }

    public function Playlist()
    {
        return $this->belongsTo(Playlist::class);

    }
    public function Song()
    {
        return $this->belongsTo(Song::class);

    }
}
