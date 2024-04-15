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
        //$gameScheduling = GameScheduling::whereDoesntHave('game')->inRandomOrder()->first();
        $gameScheduling = GameScheduling::all();
        $results = [
            'Ganó A',
            'Ganó B',
            'Empate',
            'Ganó A por W.O.',
            'Ganó B por W.O.',
            'Partido Cancelado',
        ];
        return [
            'result' => $this->faker->randomElement($results), // Seleccionar una opción aleatoria de $results
            'observation' => $this->faker->text(100),
            'game_scheduling_id' => $gameScheduling->id,
        ];
    }
}
