<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameRoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
       /*  return [
            'id' => $this->id,
            'name' => $this->name,
            'date' => $this->date,
            'tournament_id' => $this->tournament_id,
            'pitch_id' => $this->pitch_id,
            'users' => UserResource::collection($this->whenLoaded('users')),
            'gameSchedulings' => GameSchedulingResource::collection($this->whenLoaded('gameSchedulings')),
            'tournament' => new TournamentResource($this->whenLoaded('tournament')),
            'pitch' => new PitchResource($this->whenLoaded('pitch')),
            // Agrega otras propiedades según sea necesario
        ]; */
    }
}
