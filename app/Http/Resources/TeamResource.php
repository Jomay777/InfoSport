<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
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
            'description' => $this->description,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'club' => new ClubResource($this->whenLoaded('club')),
            'gameSchedulingsAsTeamA' => GameSchedulingResource::collection($this->whenLoaded('gameSchedulingsAsTeamA')),
            'gameSchedulingsAsTeamB' => GameSchedulingResource::collection($this->whenLoaded('gameSchedulingsAsTeamB')),

        ];
    }
}
