<?php

namespace Database\Seeders;

use App\Models\Pitch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PitchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pitch::create([
            'name' => 'Coliseo Julia Iriarte'            
        ]);
        Pitch::create([
            'name' => 'Aniceto Arce'            
        ]);
        Pitch::create([
            'name' => 'Jardín Botánico'            
        ]);
        Pitch::create([
            'name' => 'Multideportivo'            
        ]);
        Pitch::create([
            'name' => 'Porvenir'            
        ]);
        Pitch::create([
            'name' => 'Barrio Miraflores'            
        ]);
        Pitch::create([
            'name' => '21 de diciembre'            
        ]);
        Pitch::create([
            'name' => '15 de Abril'            
        ]);
        Pitch::create([
            'name' => '19 de Abril'            
        ]);
        Pitch::create([
            'name' => 'Barrio Lindo'            
        ]);
        Pitch::create([
            'name' => 'Motomendez'            
        ]);
        Pitch::create([
            'name' => 'San Antonio'            
        ]);
        Pitch::create([
            'name' => 'Avaroa'            
        ]);
        Pitch::create([
            'name' => 'San Juan'            
        ]);
        Pitch::create([
            'name' => 'Lapacho'            
        ]);
        Pitch::create([
            'name' => 'Municipal'            
        ]);
        Pitch::create([
            'name' => 'San José'            
        ]);
    }
}
