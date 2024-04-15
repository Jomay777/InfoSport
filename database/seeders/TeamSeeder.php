<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           // Obtener todos los clubes de la base de datos
        $clubs = DB::table('clubs')->get();

        // Iterar sobre cada club para generar 3 equipos por club
        foreach ($clubs as $club) {
            for ($i = 5, $j = 1; $i <= 21; $i += 2, $j++) {
                $teamName = $club->name . ' ' . $i;
               
                // Definir los datos del equipo
                $team = [
                    'name' => $teamName,
                    'description' => 'Este equipo de ' . $club->name . ' participará en la sub ' . $i ,
                    'club_id' => $club->id,
                    'category_id' => $j, // Puedes definir la categoría según tus necesidades
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insertar el equipo en la tabla de equipos
                DB::table('teams')->insert($team);
            }
        }
    }
}
