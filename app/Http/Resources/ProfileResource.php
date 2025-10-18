<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'profile_id' => $this->id,
            'user_profile' => UserResource::make($this->whenLoaded('user')),
            'user_first_name' => $this->first_name,
            'user_last_name' => $this->last_name,
            'user_username' => $this->username,
            'avatar' => $this->avatar,
            'bio' => $this->bio,
            'user_phone' => $this->phone,
            'user_address' => $this->address,
            'user_city' => $this->city,
            'user_country' => $this->country,
            'user_birth_date' => $this->birth_date,
            'user_gender' => $this->gender,
            'last_update' => $this->updated_at,
        ];
    }
}
