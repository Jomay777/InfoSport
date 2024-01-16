<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);

        /* return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'second_name' => $this->second_name,
            'last_name' => $this->last_name,
            'mother_last_name' => $this->mother_last_name,
            'bith_date' => $this->bith_date,
            'c_i' => $this->c_i,
            'nacionality' => $this->nacionality,
            'country_birth' => $this->country_birth,
            'region_birh' => $this->region_birh,
            'state' => $this->state,

            'team' => new TeamResource($this->whenLoaded('team')),
            'photoPlayer' => new PhotoPlayerResource($this->whenLoaded('photoPlayer'))      
        ]; */
    }
}




