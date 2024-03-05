<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlayerSanction>
 */
class PlayerSanctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'yellow_cards' => $this->faker->numberBetween(0, 5), // Assuming maximum 5 yellow cards
            'red_card' => $this->faker->numberBetween(0, 1), // Assuming maximum 1 red card
            'state' => $this->faker->randomElement(['Activo', 'Inactivo', 'Suspendido']),
            'sanction' => $this->faker->text(100),
            'game_id' => function () {
                // Assuming you have Game model and you want to associate with a random game
                return \App\Models\Game::inRandomOrder()->first()->id;
            },
            'player_id' => function () {
                // Assuming you have Player model and you want to associate with a random player
                return \App\Models\Player::inRandomOrder()->first()->id;
            },
        ];
    }
}
