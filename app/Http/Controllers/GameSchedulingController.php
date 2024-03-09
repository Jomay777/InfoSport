<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameSchedulingRequest;
use App\Http\Requests\UpdateGameSchedulingRequest;
use App\Http\Resources\GameRoleResource;
use App\Http\Resources\GameSchedulingResource;
use App\Http\Resources\TeamResource;
use App\Models\GameRole;
use App\Models\GameScheduling;
use App\Models\PositionTable;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GameSchedulingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $game_schedulings = GameScheduling::with('teamA','teamB', 'gameRole.tournament.category')
            ->latest()
            ->take(20);
        if ($request->search) {
            $game_schedulings->where(function ($query) use ($request) {
                $query->where('game_schedulings.id', 'like', $request->search)
                    ->orWhere('game_schedulings.time', 'like', $request->search)
                    ->orWhereHas('gameRole', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('teamA', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('teamB', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('gameRole.tournament', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('gameRole.tournament.category', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }
    
        $game_schedulings = $game_schedulings->get();
      /*   $teams = collect(); // Crear una nueva colección para almacenar los equipos

        foreach ($game_schedulings as $game_scheduling) {
            $teams = $teams->merge($game_scheduling->teams); // Agregar los equipos de esta programación de juego a la colección
        }
        $teams = $teams->sortBy('id'); */
       // dd($game_schedulings->all());
        return Inertia::render('Admin/GameSchedulings/GameSchedulingIndex', [
            'game_schedulings' => GameSchedulingResource::collection($game_schedulings),
            'search' => $request->search,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', GameScheduling::class);
        $gameRole = GameRole::with('tournament.category')->get();
        $teams = Team::with('category')->get();
        //dd($gameRole, $teams);
        return Inertia::render('Admin/GameSchedulings/Create', [
            'teams' => TeamResource::collection($teams),
            'game_role' => GameRoleResource::collection($gameRole)
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(GameSchedulingRequest $request)
    {
        $this->authorize('create', GameScheduling::class);

        $validatedData = $request->validated();

        if ($request->has('game_role')) {
            $validatedData['game_role_id'] = $request->input('game_role.id');
            $tournamentId = $request->input('game_role.tournament.id');
        } 
        //depurando
        $teams = $request->input('teams.*.id');
        $teamAId= $teams[0];
        $teamBId= $teams[1];
        if ($request->has('teams')) {
            $validatedData['team_a_id'] = $teamAId;
        } 
        if ($request->has('teams')) {
            $validatedData['team_b_id'] = $teamBId;
        } 
       //dd($teams, $teamAId,$teamBId, $request->all(), $validatedData, $tournamentId);
       //
        
        $game_scheduling = GameScheduling::create($validatedData);

        if (!$game_scheduling) {
            // Handle game_scheduling creation errors
            return back()->withErrors(['game_scheduling' => 'Falló la creación de programación de partido.']);
        }

        //Created register to PostionTables
        // Verificar si ya existe un registro de PositionTable para el equipo A en este torneo
        $existingPositionTableA = PositionTable::where('tournament_id', $tournamentId)
        ->where('team_id', $teamAId)
        ->first();

        // Si no existe un registro, crear uno nuevo
        if (!$existingPositionTableA) {
            $positionTableTeamA = new PositionTable([
                'team_id' => $teamAId,
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
            ->where('team_id', $teamBId)
            ->first();

        if (!$existingPositionTableB) {
            $positionTableTeamB = new PositionTable([
                'team_id' => $teamBId,
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

        /* $teams = Team::whereIn('name', $request->input('teams.*.name'))->get();
        // Obtener solo los IDs de los equipos
        $teamIds = $teams->pluck('id')->toArray(); */
/*         $teams = $request->input('teams.*.id');
        $game_scheduling->teams()->sync($teams); */

        return redirect()->route('game_schedulings.index')->with('success', 'programación de partido creado correctamente');
    }
    public function show(){
        //
    }
     /**
     * Show the form for editing the specified resource.
     */
    public function edit(GameScheduling $game_scheduling): Response
    {    
        $this->authorize('update', $game_scheduling);

        $game_scheduling->load('teamA', 'teamB', 'gameRole');
        $gameRole = GameRole::with('tournament.category')->get();
        $teams = Team::with('category')->get();
        return Inertia::render('Admin/GameSchedulings/Edit', [
            'game_scheduling' => new GameSchedulingResource($game_scheduling),
            'teamA' => TeamResource::collection($teams),
            'teamB' => TeamResource::collection($teams),
            'game_role' => GameRoleResource::collection($gameRole)
        ]);
    }
      /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGameSchedulingRequest $request, string $id): RedirectResponse
    {                
        $gameScheduling = GameScheduling::find($id)->load('gameRole.tournament');
        $this->authorize('update', $gameScheduling);       

        if (!$gameScheduling) {
            return redirect()->back()->withErrors(['error' => 'Programación de partido no encontrada.']);
        }

        $validatedData = $request->validated();

        if ($request->has('game_role.id')) {
            $validatedData['game_role_id'] = $request->input('game_role.id');
            $tournamentId = $request->input('game_role.tournament_id');
        } 
        if ($request->has('teamA.id')) {
            $validatedData['team_a_id'] = $request->input('teamA.id');
            $teamAId =  $request->input('teamA.id');
        } 
        if ($request->has('teamB.id')) {
            $validatedData['team_b_id'] = $request->input('teamB.id');
            $teamBId =  $request->input('teamB.id');
        } 
        //Obtener los datos antiguos de programacion del partido
        $oldTeamAId = $gameScheduling->team_a_id;
        $oldTeamBId = $gameScheduling->team_b_id;
        $oldGameRoleId = $gameScheduling->game_role_id;
         // Obtener el ID del torneo antiguo
        $oldTournamentId = $gameScheduling->gameRole->tournament_id;
        //dd($validatedData, $request->all(), $tournamentId, $oldGameRoleId, $oldTeamAId, $oldTeamBId, $oldTournamentId, $gameScheduling);
        $gameScheduling->update($validatedData);
        

        // Obtener los nuevos datos de la programación del partido
        $newTeamAId = $request->input('teamA.id');
        $newTeamBId = $request->input('teamB.id');
        $newGameRoleId = $request->input('game_role.id');
        
        //Created register to PostionTables

        // Manejar los cambios en los equipos y el rol del partido
        if ($oldTeamAId !== $newTeamAId || $oldTeamBId !== $newTeamBId || $oldGameRoleId !== $newGameRoleId) {
            // Verificar si el equipo antiguo tiene otras programaciones de partido en el mismo torneo
            // Verificar si el equipo antiguo A tiene otras programaciones de partido en el mismo torneo
            $hasOtherGameSchedulingsA = GameScheduling::whereHas('gameRole', function ($query) use ($oldTournamentId, $oldTeamAId, $id) {
                $query->where('tournament_id', $oldTournamentId) // Filtra por el antiguo ID del torneo
                    ->where(function ($query) use ($oldTeamAId, $id) {
                        $query->where('team_a_id', $oldTeamAId)
                                ->orWhere('team_b_id', $oldTeamAId)
                                ->where('id', '!=', $id); // Excluye la programación de partido actual
                    });
            })->exists();

            // Verificar si el equipo antiguo B tiene otras programaciones de partido en el mismo torneo
            $hasOtherGameSchedulingsB = GameScheduling::whereHas('gameRole', function ($query) use ($oldTournamentId, $oldTeamBId, $id) {
                $query->where('tournament_id', $oldTournamentId) // Filtra por el antiguo ID del torneo
                    ->where(function ($query) use ($oldTeamBId, $id) {
                        $query->where('team_a_id', $oldTeamBId)
                                ->orWhere('team_b_id', $oldTeamBId)
                                ->where('id', '!=', $id); // Excluye la programación de partido actual
                    });
            })->exists();

            // Eliminar los registros antiguos de las tablas de posiciones si el equipo antiguo A no tiene otras programaciones de partido en el mismo torneo
            if (!$hasOtherGameSchedulingsA) {
                PositionTable::where('tournament_id', $oldTournamentId)
                    ->where('team_id', $oldTeamAId)
                    ->delete();
            }

            // Eliminar los registros antiguos de las tablas de posiciones si el equipo antiguo B no tiene otras programaciones de partido en el mismo torneo
            if (!$hasOtherGameSchedulingsB) {
                PositionTable::where('tournament_id', $oldTournamentId)
                    ->where('team_id', $oldTeamBId)
                    ->delete();
            }

            // Verificar si ya existe un registro de PositionTable para el equipo A en este torneo
            $existingPositionTableA = PositionTable::where('tournament_id', $tournamentId)
            ->where('team_id', $newTeamAId)
            ->first();

            // Crear un nuevo registro solo si no existe un registro previo para el equipo A en este torneo
            if (!$existingPositionTableA) {
            $positionTableTeamA = new PositionTable([
                'team_id' => $newTeamAId,
                'tournament_id' => $tournamentId,
                // Otros campos de la tabla de posiciones
            ]);
            $positionTableTeamA->save();
            }

            // Realizar la misma verificación y creación para el equipo B
            $existingPositionTableB = PositionTable::where('tournament_id', $tournamentId)
            ->where('team_id', $newTeamBId)
            ->first();

            if (!$existingPositionTableB) {
            $positionTableTeamB = new PositionTable([
                'team_id' => $newTeamBId,
                'tournament_id' => $tournamentId,
                // Otros campos de la tabla de posiciones
            ]);
            $positionTableTeamB->save();
            }
        }

        return redirect()->route('game_schedulings.index')->with('success', 'Programación de partido actualizada correctamente');        
    }
     /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameScheduling $game_scheduling): RedirectResponse
    {
        $this->authorize('delete', $game_scheduling);
        
          // Obtener los datos necesarios para eliminar los registros de PositionTable
        $tournamentId = $game_scheduling->gameRole->tournament_id;
        $teamAId = $game_scheduling->team_a_id;
        $teamBId = $game_scheduling->team_b_id;
        $gameSchedulingId = $game_scheduling->id;
        //dd($teamAId,$teamBId,$tournamentId,$gameSchedulingId);
        // Verificar si hay otras programaciones de partido para los equipos en el mismo torneo
        $hasOtherGameSchedulingsA = GameScheduling::whereHas('gameRole', function ($query) use ($tournamentId, $teamAId, $gameSchedulingId) {
            $query->where('tournament_id', $tournamentId) // Filtra por el antiguo ID del torneo
                ->where(function ($query) use ($teamAId, $gameSchedulingId) {
                    $query->where('team_a_id', $teamAId)
                            ->orWhere('team_b_id', $teamAId)
                            ; // Excluye la programación de partido actual
                });
        })->where('id', '!=', $gameSchedulingId)->exists();

        // Verificar si el equipo antiguo B tiene otras programaciones de partido en el mismo torneo
        $hasOtherGameSchedulingsB = GameScheduling::whereHas('gameRole', function ($query) use ($tournamentId, $teamBId, $gameSchedulingId) {
            $query->where('tournament_id', $tournamentId) // Filtra por el antiguo ID del torneo
                ->where(function ($query) use ($teamBId, $gameSchedulingId) {
                    $query->where('team_a_id', $teamBId)
                            ->orWhere('team_b_id', $teamBId)
                            ; // Excluye la programación de partido actual
                });
        })->where('id', '!=', $gameSchedulingId)->exists();
        //dd($hasOtherGameSchedulingsA, $hasOtherGameSchedulingsB);
        // Eliminar los registros de PositionTable si no hay otras programaciones de partido para los equipos en el mismo torneo
        if (!$hasOtherGameSchedulingsA) {
            PositionTable::where('tournament_id', $tournamentId)
                ->where('team_id', $teamAId)
                ->delete();
        }

        if (!$hasOtherGameSchedulingsB) {
            PositionTable::where('tournament_id', $tournamentId)
                ->where('team_id', $teamBId)
                ->delete();
        }
        //$game_scheduling->teams()->detach();
        $game_scheduling->delete();
        return to_route('game_schedulings.index');
    }
}
