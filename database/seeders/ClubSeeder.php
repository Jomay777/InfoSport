<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Define los datos de ejemplo para los clubs
          $clubs = [
            [
                'name' => 'Real Madrid',
                'coach' => 'Carlo Ancelotti',
                'logo_path' => 'real_madrid_logo.png',
            ],
            [
                'name' => 'FC Barcelona',
                'coach' => 'Xavi Hernandez',
                'logo_path' => 'barcelona_logo.png',
            ],
            [
                'name' => 'Liverpool FC',
                'coach' => 'Jurgen Klopp',
                'logo_path' => 'liverpool_logo.png',
            ],
            [
                'name' => 'Paris Saint-Germain',
                'coach' => 'Mauricio Pochettino',
                'logo_path' => 'psg_logo.png',
            ],
            [
                'name' => 'Chelsea FC',
                'coach' => 'Thomas Tuchel',
                'logo_path' => 'chelsea_logo.png',
            ],
            [
                'name' => 'Bayern Munich',
                'coach' => 'Hans-Dieter Flick',
                'logo_path' => 'bayern_munich_logo.png',
            ],
            [
                'name' => 'Manchester City',
                'coach' => 'Pep Guardiola',
                'logo_path' => 'manchester_city_logo.png',
            ],
            [
                'name' => 'Juventus FC',
                'coach' => 'Massimiliano Allegri',
                'logo_path' => 'juventus_logo.png',
            ],
            [
                'name' => 'Ajax Amsterdam',
                'coach' => 'Erik ten Hag',
                'logo_path' => 'ajax_logo.png',
            ],
            [
                'name' => 'Atletico Madrid',
                'coach' => 'Diego Simeone',
                'logo_path' => 'atletico_madrid_logo.png',
            ],
            // Agrega más clubs según sea necesario
        ];

        // Inserta los datos en la tabla de clubs
        DB::table('clubs')->insert($clubs);
    }
}
