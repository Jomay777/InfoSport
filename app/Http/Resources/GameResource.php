<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
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
            'result' => $this->result,
            'observation' => $this->observation,
            'game_scheduling_id' => $this->game_scheduling_id,
            'users' => UserResource::collection($this->whenLoaded('users')),
            'gameScheduling' => new GameSchedulingResource($this->whenLoaded('gameScheduling')),
            'gameStatistic' => new GameStatisticResource($this->whenLoaded('gameStatistic')),
            // Agrega otras propiedades según sea necesario
        ];  */
    }
}
