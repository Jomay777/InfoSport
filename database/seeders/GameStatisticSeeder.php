<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameStatistic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GameStatisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Obtener todos los juegos
        $games = Game::all();

        foreach ($games as $game) {
            // Crear una estadística de juego para cada juego
            GameStatistic::create([
                'goals_team_a' => $faker->numberBetween(0, 5),
                'goals_team_b' => $faker->numberBetween(0, 5),
                'yellow_cards_a' => $faker->numberBetween(0, 5),
                'yellow_cards_b' => $faker->numberBetween(0, 5),
                'red_cards_a' => $faker->numberBetween(0, 2),
                'red_cards_b' => $faker->numberBetween(0, 2),
                'game_id' => $game->id,
            ]);
        }
    }
}
