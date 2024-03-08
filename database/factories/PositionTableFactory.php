<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PositionTable>
 */
class PositionTableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'points' => $this->faker->numberBetween(0, 100),
            'games_played' => $this->faker->numberBetween(0, 20),
            'games_won' => $this->faker->numberBetween(0, 20),
            'games_drawn' => $this->faker->numberBetween(0, 20),
            'games_lost' => $this->faker->numberBetween(0, 20),
            'goals_scored' => $this->faker->numberBetween(0, 50),
            'goals_against' => $this->faker->numberBetween(0, 50),
            'tournament_id' => function () {
                return \App\Models\Tournament::inRandomOrder()->first()->id;
            },
            'team_id' => function () {
                return \App\Models\Team::inRandomOrder()->first()->id;
            },
        ];
    }
}
