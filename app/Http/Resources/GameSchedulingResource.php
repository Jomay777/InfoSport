<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameSchedulingResource extends JsonResource
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
            'time' => $this->time,
            'game_role_id' => $this->game_role_id,
            //'users' => UserResource::collection($this->whenLoaded('users')),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            'gameRole' => new GameRoleResource($this->whenLoaded('gameRole')),
            'game' => new GameResource($this->whenLoaded('game')),
            // Agrega otras propiedades según sea necesario
        ];   
    }
}
