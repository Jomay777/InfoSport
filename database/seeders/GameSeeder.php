<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameScheduling;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {        
        // Obtener un GameScheduling que aún no esté relacionado con ningún Game
        $gameSchedulings = GameScheduling::all();

        $faker = Faker::create();

        foreach ($gameSchedulings as $gameScheduling) {
            // Generar 10 juegos para cada GameScheduling
                Game::create([
                    'result' => $faker->randomElement(['Ganó A', 'Ganó B', 'Empate', 'Ganó A por W.O.', 'Ganó B por W.O.', 'Partido Cancelado']),
                    'observation' => $faker->text(100),
                    'game_scheduling_id' => $gameScheduling->id,
                ]);
            
        }
    }
}
