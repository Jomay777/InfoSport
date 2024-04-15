<?php

namespace Database\Seeders;

use App\Models\Tournament;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los torneos de la base de datos
        $tournaments = Tournament::all();

        // Iterar sobre cada torneo para generar 3 roles de juego por torneo
        foreach ($tournaments as $j => $tournament) {
            $j = $j + 1;
            for ($i = 1; $i <= 3; $i++) {
                $roleName = "Fecha $i";

                // Calcular la fecha sumando $i días al número de torneo
                $date = Carbon::now()->addDays($i + $j);

                // Definir los datos del rol de juego
                $gameRole = [
                    'name' => $roleName,
                    'date' => $date, // Utilizamos la fecha calculada
                    'tournament_id' => $tournament->id,
                    'pitch_id' => $i, // Esto se puede ajustar según tus necesidades
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insertar el rol de juego en la tabla de gamerole
                DB::table('game_roles')->insert($gameRole);
            }
        }

    }
}
