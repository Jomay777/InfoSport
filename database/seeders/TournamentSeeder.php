<?php

namespace Database\Seeders;

use App\Models\Category;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nombres de torneos conocidos
        $tournamentNames = [
            'FIFA World Cup',
            'UEFA Champions League',
            'Copa Libertadores',
            'Premier League',
            'La Liga',
        ];

        // Genera datos de torneos de ejemplo
        $tournamentData = [];
        $faker = Factory::create();

        // Itera para generar 5 torneos
        foreach ($tournamentNames as $name) {
            $tournamentData[] = [
                'name' => $name,
                'state' => $faker->randomElement(['Publicado', 'No publicado']),
                'description' => $faker->realText(300),
                'category_id' => Category::all()->random()->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Inserta los datos en la tabla de torneos
        DB::table('tournaments')->insert($tournamentData);
    }
}
