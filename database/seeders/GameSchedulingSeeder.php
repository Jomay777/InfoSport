<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GameRole;
use App\Models\PositionTable;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSchedulingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las categorías de la base de datos
        $categories = Category::all();

        // Iterar sobre cada categoría para programar 5 partidos
        foreach ($categories as $category) {
            // Obtener todos los equipos de la misma categoría
            $teams = Team::where('category_id', $category->id)->get();

            // Verificar que haya al menos 2 equipos para programar partidos
            if ($teams->count() >= 2) {
                // Iterar para programar 5 partidos
                for ($i = 1; $i <= 8; $i++) {
                    // Seleccionar dos equipos aleatorios de la lista de equipos
                    $teamA = $teams->random();
                    $teamB = $teams->except($teamA->id)->random(); // Evitar que el equipo B sea el mismo que el equipo A

                    // Crear una hora aleatoria para el partido
                    $time = Carbon::createFromTime(rand(8, 20), rand(0, 59), 0);

                    // Obtener un rol de juego aleatorio para asignar al partido
                    /* $gameRole = GameRole::all();
                    $gameRole = $gameRole->load('tournament');
                    $gameRole = GameRole::where('tournament.category_id', $category->id)->inRandomOrder()->first();
 */
                    $gameRole = GameRole::whereHas('tournament', function ($query) use ($teamA) {
                        $query->where('category_id', $teamA->category_id);
                    })->inRandomOrder()->first();
                    if($gameRole){
                        // Definir los datos del partido
                        $gameScheduling = [
                            'time' => $time,
                            'game_role_id' => $gameRole->id,
                            'team_a_id' => $teamA->id,
                            'team_b_id' => $teamB->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    
                        // Insertar el partido programado en la tabla de game_schedulings
                        DB::table('game_schedulings')->insert($gameScheduling);

                        // Verificar si ya existe un registro de PositionTable para el equipo A en este torneo
                        $tournamentId = $gameRole->tournament_id;
                        $existingPositionTableA = PositionTable::where('tournament_id', $tournamentId)
                        ->where('team_id', $teamA->id)
                        ->first();

                        // Si no existe un registro, crear uno nuevo
                        if (!$existingPositionTableA) {
                            $positionTableTeamA = new PositionTable([
                                'team_id' => $teamA->id,
                                'points' => 0,
                                'games_played' => 0,
                                'games_won' => 0,
                                'games_drawn' => 0,
                                'games_lost' => 0,
                                'goals_scored' => 0,
                                'goals_against' => 0,
                                'tournament_id' => $tournamentId,
                            ]);
                            $positionTableTeamA->save();
                        }

                        // Realizar la misma verificación y creación para el equipo B
                        $existingPositionTableB = PositionTable::where('tournament_id', $tournamentId)
                            ->where('team_id', $teamB->id)
                            ->first();

                        if (!$existingPositionTableB) {
                            $positionTableTeamB = new PositionTable([
                                'team_id' => $teamB->id,
                                'points' => 0,
                                'games_played' => 0,
                                'games_won' => 0,
                                'games_drawn' => 0,
                                'games_lost' => 0,
                                'goals_scored' => 0,
                                'goals_against' => 0,
                                'tournament_id' => $tournamentId,
                            ]);
                            $positionTableTeamB->save();
                        }
                    }
                    
                    
                    
                }
            }
        }
    }
}
