<?php

namespace Modules\Playlist\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'song' => $this->song->title,
            'order_number' => $this->order_number
        ];
    }
}
