<?php

namespace Database\Factories;

use App\Models\GameScheduling;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GameSchedulingTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_scheduling_id' => GameScheduling::all()->random()->id,
            'team_id' => Team::all()->random()->id,
        ];
    }
}
