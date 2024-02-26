<?php

namespace Modules\User\App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\User\App\Enums\UserPlan;
use Modules\User\App\Enums\UserStatus;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'status' => UserStatus::getKey((int) $this->user_status),
            'token' => $this->token,
        ];
    }
}
