<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamSanction>
 */
class TeamSanctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'state' => $this->faker->randomElement(['Activo', 'Inactivo', 'Suspendido']),
            'sanction' => $this->faker->sentence(),
            'observation' => $this->faker->paragraph(),
            'game_id' => function () {
                // Assuming you have Game model and there are games available
                return \App\Models\Game::inRandomOrder()->first()->id;
            },
            'team_id' => function () {
                // Assuming you have Team model and there are teams available
                return \App\Models\Team::inRandomOrder()->first()->id;
            },
        ];
    }
}
