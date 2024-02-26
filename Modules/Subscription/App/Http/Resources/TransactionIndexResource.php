<?php

namespace Modules\Subscription\App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
