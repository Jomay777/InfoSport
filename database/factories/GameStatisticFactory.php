<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class GameStatisticFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $game = Game::whereDoesntHave('gameStatistic')->inRandomOrder()->first();
        return [
            'goals_team_a' => $this->faker->numberBetween(0, 10),
            'goals_team_b' => $this->faker->numberBetween(0, 10),
            'yellow_cards_a' => $this->faker->numberBetween(0, 5),
            'yellow_cards_b' => $this->faker->numberBetween(0, 5),
            'red_cards_a' => $this->faker->numberBetween(0, 2),
            'red_cards_b' => $this->faker->numberBetween(0, 2),
            'game_id' =>   $game->id,
        ];
    }
}
