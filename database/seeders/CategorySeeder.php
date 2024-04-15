<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Category::create([
            'name' => '5',
            'description' => 'Esta categoria es especificamente para jugadores menores de 5 años'
        ]); */
        $categories = [
            [
                'name' => '5',
                'description' => 'Esta categoria es especificamente para jugadores menores de 5 años',
            ],
            [
                'name' => '7',
                'description' => 'Esta categoria es especificamente para jugadores menores de 7 años',
            ],
            [
                'name' => '9',
                'description' => 'Esta categoria es especificamente para jugadores menores de 9 años',
            ],
            [
                'name' => '11',
                'description' => 'Esta categoria es especificamente para jugadores menores de 11 años',
            ], [
                'name' => '13',
                'description' => 'Esta categoria es especificamente para jugadores menores de 5 años',
            ],
            [
                'name' => '15',
                'description' => 'Esta categoria es especificamente para jugadores menores de 15 años',
            ],
            [
                'name' => '17',
                'description' => 'Esta categoria es especificamente para jugadores menores de 17 años',
            ],
            [
                'name' => 'Primera división',
                'description' => 'En esta categoría pueden ingresar jugadores de cualquier edad',
            ],
            [
                'name' => 'Segunda división',
                'description' => 'En esta categoría pueden ingresar jugadores de cualquier edad',
            ],
            // Puedes agregar más categorías según sea necesario
        ];

        // Inserta los datos en la tabla de categorías
        DB::table('categories')->insert($categories);
    }
}
