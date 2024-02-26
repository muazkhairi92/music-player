<?php

namespace Modules\Playlist\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Playlist\App\Http\Requests\PostPlaylistRequest;
use Modules\Playlist\App\Http\Requests\PutPlaylistRequest;
use Modules\Playlist\App\Models\Playlist;
use Modules\Playlist\App\Models\PlayListList;
use Modules\Playlist\App\Http\Resources\PlaylistResource;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $playlists = Playlist::all();

        return PlaylistResource::collection($playlists);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostPlaylistRequest $request)
    {
        $user = Auth::user();
        if (!$user->subscription->isPremium) {
            return $this->sendError('User not allowed to make playlist', null, 403);
        }
        $playlist = Playlist::create([
            'user_id' => $user->id,
            'name' => $request->name,
        ]);
        foreach ($request->list as $list) {
            $list = PlayListList::create([
                'playlist_id' => $playlist->id,
                'song_id' => $list['song_id'],
                'order_number' => $list['order_number'],
            ]);
        }

        return $this->sendResponse('playlist successfully create', new PlaylistResource($playlist), 200);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('playlist::show');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PutPlaylistRequest $request, $id)
    {
        $playlist = Auth::user()->playlist->findOrFail($id);
        $playlist->PlaylistList()->delete();
        foreach ($request->list as $list) {
            $list = PlayListList::create([
                'playlist_id' => $playlist->id,
                'song_id' => $list->song_id,
                'order_number' => $list->order_number,
            ]);
        }

        return $this->sendResponse('playlist successfully updated', new PlaylistResource($playlist), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Auth::user()->playlist->findOrFail($id)->delete();

        return $this->sendResponse('playlist successfully deleted', null, 200);
    }
}
