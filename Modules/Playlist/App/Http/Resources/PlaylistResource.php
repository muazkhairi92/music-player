<?php

namespace Modules\Playlist\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PlaylistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        $lists = collect($this->PlaylistList);

        return [
            'id' => $this->id,
            'title' => $this->name,
            'list' => ListResource::collection($lists),
        ];
    }
}
