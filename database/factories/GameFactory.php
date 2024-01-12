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
            'game_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'game_time' => $this->faker->time('H:i'),
            'location' => $this->faker->city,
            'result' => $this->faker->randomNumber(2) . '-' . $this->faker->randomNumber(2),
            'observation' => $this->faker->text(200),
            'game_scheduling_id' => GameScheduling::all()->random()->id,
            'tournament_id' => Tournament::all()->random()->id,
        ];
    }
}
