<?php

namespace Database\Factories;

use App\Models\GameRole;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GameScheduling>
 */
class GameSchedulingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'time' => $this->faker->time(),
            'team_a_id' => Team::all()->random()->id,
            'team_b_id' => Team::all()->random()->id,

            'game_role_id' => GameRole::all()->random()->id,
        ];
    }
}
