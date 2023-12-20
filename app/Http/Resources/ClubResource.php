<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClubResource extends JsonResource
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
            'coach' => $this->coach,
            'logo_path' => $this->logo_path,
            //'users' => UserResource::collection($this->users),
            'users' => UserResource::collection($this->whenLoaded('users')),
        ];
    }
}
