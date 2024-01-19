<?php

namespace Database\Factories;

use App\Models\GameScheduling;
use App\Models\Tournament;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'result' => $this->faker->randomNumber(2) . '-' . $this->faker->randomNumber(2),
            'observation' => $this->faker->text(200),
            'game_scheduling_id' => GameScheduling::all()->random()->id,
        ];
    }
}
