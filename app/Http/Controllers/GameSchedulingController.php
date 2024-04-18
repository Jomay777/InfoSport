<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameSchedulingRequest;
use App\Http\Requests\UpdateGameSchedulingRequest;
use App\Http\Resources\GameRoleResource;
use App\Http\Resources\GameSchedulingResource;
use App\Http\Resources\PositionTableResource;
use App\Http\Resources\TeamResource;
use App\Models\GameRole;
use App\Models\GameScheduling;
use App\Models\LogTransaction;
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
                        $subQuery->where('name', 'like', $request->search);
                    })
                    ->orWhereHas('teamA', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search . '%');
                    })
                    ->orWhereHas('teamB', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search . '%');
                    })
                    ->orWhereHas('gameRole.tournament', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('gameRole.tournament.category', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search);
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
            $gameRole = GameRole::find($request->input('game_role.id'));
            $gameRole = $gameRole->load('tournament');
            $tournamentId = $gameRole->tournament->id;
        //    $tournamentId = $request->input('game_role.tournament.id');
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
        //Creating log Transaction
        $details = 'Hora: ' . $game_scheduling->time . ', Id de Rol de Partido: ' . $game_scheduling->game_role_id. ', Id de Equipo A: ' . $game_scheduling->team_a_id. ', Id de Equipo B: ' . $game_scheduling->team_b_id;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Crear', // HTTP method used for the request
            'resource' => 'Programación de partido', // Name of the resource being accessed
            'resource_id' => $game_scheduling?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  

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
            $details = 'Id de Equipo: ' . $teamAId. ', Id de Torneo: ' . $tournamentId;
            LogTransaction::create([
                'user_id' => auth()->id(), // Assuming you have user authentication
                'action' => 'Crear', // HTTP method used for the request
                'resource' => 'Posición de Equipo', // Name of the resource being accessed
                'resource_id' => $positionTableTeamA?->id, // ID of the resource, if applicable
                'details' => $details, // Any additional details you want to log
            ]); 
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
            //log create
            $details = 'Id de Equipo: ' . $teamBId. ', Id de Torneo: ' . $tournamentId;
            LogTransaction::create([
                'user_id' => auth()->id(), // Assuming you have user authentication
                'action' => 'Crear', // HTTP method used for the request
                'resource' => 'Posición de Equipo', // Name of the resource being accessed
                'resource_id' => $positionTableTeamB?->id, // ID of the resource, if applicable
                'details' => $details, // Any additional details you want to log
            ]); 
        }

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

        $game_scheduling = $game_scheduling->load('teamA.positionTables', 'teamB', 'gameRole.tournament.category');
        $gameRole = GameRole::with('tournament.category', 'tournament.positionTables')->get();
        $teams = Team::with('category','positionTables')->get();
        $positionTables = PositionTable::with('team','tournament')->get();

        //$posiT = $gameRole->tournament?->positionTables;
        //dd($teams, $gameRole, $game_scheduling);
        return Inertia::render('Admin/GameSchedulings/Edit', [
            'game_scheduling' => new GameSchedulingResource($game_scheduling),
            'teamA' => TeamResource::collection($teams),
            'teamB' => TeamResource::collection($teams),
            'game_role' => GameRoleResource::collection($gameRole),
            'position_tables' => PositionTableResource::collection($positionTables)
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

        $oldTime = $gameScheduling->time;
        //dd($validatedData, $request->all(), $tournamentId, $oldGameRoleId, $oldTeamAId, $oldTeamBId, $oldTournamentId, $gameScheduling);
        $gameScheduling->update($validatedData);

        //Creating log Transaction
        $details = '[Hora: ' . $oldTime . '. a: ' . $gameScheduling->time . '], [Id de Rol de Rartido: ' . $oldGameRoleId . '. a: ' . $gameScheduling->game_role_id . '], [Id de Equipo A: ' . $oldTeamAId . '. a: ' . $gameScheduling->team_a_id . '], [Id de Equipo B: ' . $oldTeamBId . '. a: ' . $gameScheduling->team_b_id . ']';
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Actualizar', // HTTP method used for the request
            'resource' => 'Programación de partido', // Name of the resource being accessed
            'resource_id' => $gameScheduling?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  

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
                $positionTableTeamA = PositionTable::where('tournament_id', $oldTournamentId)
                    ->where('team_id', $oldTeamAId);
                PositionTable::where('tournament_id', $oldTournamentId)
                    ->where('team_id', $oldTeamAId)
                    ->delete();
                    $details = 'Id de Equipo: ' . $oldTeamAId. ', Id de Torneo: ' . $oldTournamentId;
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Eliminar', // HTTP method used for the request
                        'resource' => 'Posición de Equipo', // Name of the resource being accessed
                        'resource_id' => $positionTableTeamA?->id, // ID of the resource, if applicable
                        'details' => $details, // Any additional details you want to log
                    ]); 
            }

            // Eliminar los registros antiguos de las tablas de posiciones si el equipo antiguo B no tiene otras programaciones de partido en el mismo torneo
            if (!$hasOtherGameSchedulingsB) {
                $positionTableTeamB = PositionTable::where('tournament_id', $oldTournamentId)
                    ->where('team_id', $oldTeamBId);
                PositionTable::where('tournament_id', $oldTournamentId)
                    ->where('team_id', $oldTeamBId)
                    ->delete();
                //create log
                $details = 'Id de Equipo: ' . $oldTeamBId. ', Id de Torneo: ' . $oldTournamentId;
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Eliminar', // HTTP method used for the request
                        'resource' => 'Posición de Equipo', // Name of the resource being accessed
                        'resource_id' => $positionTableTeamB?->id, // ID of the resource, if applicable
                        'details' => $details, // Any additional details you want to log
                    ]); 
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
                //create log
                $details = 'Id de Equipo: ' . $newTeamAId. ', Id de Torneo: ' . $tournamentId;
                LogTransaction::create([
                    'user_id' => auth()->id(), // Assuming you have user authentication
                    'action' => 'Crear', // HTTP method used for the request
                    'resource' => 'Posición de Equipo', // Name of the resource being accessed
                    'resource_id' => $positionTableTeamA?->id, // ID of the resource, if applicable
                    'details' => $details, // Any additional details you want to log
                ]); 

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
                //create log
                $details = 'Id de Equipo: ' . $newTeamBId. ', Id de Torneo: ' . $tournamentId;
                LogTransaction::create([
                    'user_id' => auth()->id(), // Assuming you have user authentication
                    'action' => 'Crear', // HTTP method used for the request
                    'resource' => 'Posición de Equipo', // Name of the resource being accessed
                    'resource_id' => $positionTableTeamB?->id, // ID of the resource, if applicable
                    'details' => $details, // Any additional details you want to log
                ]); 
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
        if($hasOtherGameSchedulingsA || $hasOtherGameSchedulingsB){
             //Quitar goles puntos, y demas a los equipos en la tabla de posiciones
             //Charged PostionTable
            $game_scheduling = $game_scheduling->load('teamA.positionTables','teamB.positionTables', 'gameRole.tournament.positionTables','game.gameStatistic');

            // Procesamiento del resultado del partido
            $resultName = $game_scheduling?->game?->result;
            $teamAWon = $resultName === 'Ganó A';
            $teamBWon = $resultName === 'Ganó B';
            $drawn = $resultName === 'Empate';
            // Obtener goles de equipo A y B
            $goalsTeamA =$game_scheduling?->game?->gameStatistic?->goals_team_a;
            $goalsTeamB =$game_scheduling?->game?->gameStatistic?->goals_team_b;

            //teamA WIN
            //$positionTable = PositionTable::find
            // Lógica para actualizar las posiciones de los equipos en la tabla de posiciones
            if ($teamAWon || $teamBWon) {
            //aqui hacer los procesos cuando teamA gana
            
                $winningTeamId = $teamAWon ? $teamAId : $teamBId;
                $losingTeamId = $teamAWon ? $teamBId : $teamAId;
                //goles del equipo ganador y perdedor
                $goalsWinningTeam = $teamAWon ? $goalsTeamA : $goalsTeamB;
                $goalsLosingTeam = $teamAWon ? $goalsTeamB : $goalsTeamA;
                // Encontrar el registro de PositionTable relacionado con el equipo ganador y el torneo
                $winningTeamPosition = PositionTable::where('team_id', $winningTeamId)
                    ->where('tournament_id', $tournamentId)
                    ->first();
                //['points', 'games_played', 'games_won', 'games_drawn', 'games_lost', 'goals_scored', 'goals_against', 'tournament_id', 'team_id',];
                if($winningTeamPosition){
                    $winningTeamPosition->points -= 3;
                    $winningTeamPosition->games_played -= 1;
                    $winningTeamPosition->games_won -= 1;
                    $winningTeamPosition->goals_scored -= $goalsWinningTeam;
                    $winningTeamPosition->goals_against -= $goalsLosingTeam;
                    $winningTeamPosition->save();
                     //create log
                    $details = 'Id de Equipo: ' . $winningTeamPosition->team_id. ', Id de Torneo: ' . $winningTeamPosition->tournament_id 
                        . ', Pts:'.' -3'.', Part Jugados: -1'. ', Part Ganados: -1, Goles a Favor: -'. $goalsWinningTeam . ', Goles en Contra: -'. $goalsLosingTeam;
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Actualizar', // HTTP method used for the request
                        'resource' => 'Tabla de posición', // Name of the resource being accessed
                        'resource_id' => $winningTeamPosition?->id, // ID of the resource, if applicable
                        'details' => $details, // Any additional details you want to log
                    ]); 
                }
                //encontrar el registro de positionTable relacionado con el equipo perdedor y el torneo

                $losingTeamPosition = PositionTable::where('team_id', $losingTeamId)
                ->where('tournament_id', $tournamentId)
                ->first();
                if($losingTeamPosition){
                    $losingTeamPosition->games_played -= 1;
                    $losingTeamPosition->games_lost -= 1;
                    $losingTeamPosition->goals_scored -= $goalsLosingTeam;
                    $losingTeamPosition->goals_against -= $goalsWinningTeam;
                    // Actualiza los demás campos según la lógica de tu aplicación
                    $losingTeamPosition->save();
                    $details = 'Id de Equipo: ' . $losingTeamPosition->team_id. ', Id de Torneo: ' . $losingTeamPosition->tournament_id 
                        . ', Part Jugados: -1'. ', Part Perdidos: -1, Goles a Favor: -'. $goalsLosingTeam . ', Goles en Contra: -'. $goalsWinningTeam;
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Actualizar', // HTTP method used for the request
                        'resource' => 'Tabla de posición', // Name of the resource being accessed
                        'resource_id' => $losingTeamPosition->id, // ID of the resource, if applicable
                        'details' => $details, // Any additional details you want to log
                    ]); 
                }
            //dd($game);
            }
            //Empate
            elseif ($drawn) {
            //aqui hacer los procesos cuando teamB gana

                // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
                $teamAPosition = PositionTable::where('team_id', $teamAId)
                    ->where('tournament_id', $tournamentId)
                    ->first();

                // Quitar los juegos jugados y los juegos empatados para el equipo A
                if($teamAPosition){
                    $teamAPosition->points -= 1;
                    $teamAPosition->games_played -= 1;
                    $teamAPosition->games_drawn -= 1;
                    $teamAPosition->goals_scored -= $goalsTeamA;
                    $teamAPosition->goals_against -= $goalsTeamB;
                    $teamAPosition->save();
                    $details = 'Id de Equipo: ' . $teamAPosition->team_id. ', Id de Torneo: ' . $teamAPosition->tournament_id 
                        . ', Pts:'.' -1'.', Part Jugados: -1'. ', Part Empatados: -1, Goles a Favor: -'. $goalsTeamA . ', Goles en Contra: -'. $goalsTeamB;
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Actualizar', // HTTP method used for the request
                        'resource' => 'Tabla de posición', // Name of the resource being accessed
                        'resource_id' => $teamAPosition->id, // ID of the resource, if applicable
                        'details' => $details, // Any additional details you want to log
                    ]); 
                }
                // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
                $teamBPosition = PositionTable::where('team_id', $teamBId)
                    ->where('tournament_id', $tournamentId)
                    ->first();

                // Quitar los juegos jugados y los juegos empatados para el equipo B
                if($teamBPosition){
                    $teamBPosition->points -= 1;
                    $teamBPosition->games_played -= 1;
                    $teamBPosition->games_drawn -= 1;
                    $teamBPosition->goals_scored -= $goalsTeamB;
                    $teamBPosition->goals_against -= $goalsTeamA;
                    $teamBPosition->save();
                    $details = 'Id de Equipo: ' . $teamBPosition->team_id. ', Id de Torneo: ' . $teamBPosition->tournament_id 
                        . ', Pts:'.' -1'.', Part Jugados: -1'. ', Part Empatados: -1, Goles a Favor: -'. $goalsTeamB . ', Goles en Contra: -'. $goalsTeamA;
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Actualizar', // HTTP method used for the request
                        'resource' => 'Tabla de posición', // Name of the resource being accessed
                        'resource_id' => $teamBPosition->id, // ID of the resource, if applicable
                        'details' => $details, // Any additional details you want to log
                    ]); 
                }
            }

            //teamA Win to W.O.
            if ($resultName === 'Ganó A por W.O.') {            
                // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
                $teamAPosition = PositionTable::where('team_id', $teamAId)
                ->where('tournament_id', $tournamentId)
                ->first();

                // Quitar los juegos jugados y los juegos ganados para el equipo A
                if($teamAPosition){
                    $teamAPosition->points -= 3;
                    $teamAPosition->games_played -= 1;
                    $teamAPosition->games_won -= 1;
                    $teamAPosition->goals_scored -= 3;
                    $teamAPosition->save();
                    //log
                    $details = 'Id de Equipo: ' . $teamAPosition->team_id. ', Id de Torneo: ' . $teamAPosition->tournament_id 
                        . ', Pts:'.' -3'.', Part Jugados: -1'. ', Part Ganados: -1, Goles a Favor: -3' ;
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Actualizar', // HTTP method used for the request
                        'resource' => 'Tabla de posición', // Name of the resource being accessed
                        'resource_id' => $teamAPosition->id, // ID of the resource, if applicable
                        'details' => $details, // Any additional details you want to log
                    ]); 
                }
                // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
                $teamBPosition = PositionTable::where('team_id', $teamBId)
                    ->where('tournament_id', $tournamentId)
                    ->first();
                // Quitar los juegos jugados y los juegos perdidos para el equipo B
                if($teamBPosition){
                    $teamBPosition->games_played -= 1;            
                    $teamBPosition->games_lost -= 1;            
                    $teamBPosition->goals_against -= 3;            

                    $teamBPosition->save();
                    //log
                    $details = 'Id de Equipo: ' . $teamBPosition->team_id. ', Id de Torneo: ' . $teamBPosition->tournament_id 
                        .', Part Jugados: -1'. ', Part Perdidos: -1, Goles en Contra: -3';
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Actualizar', // HTTP method used for the request
                        'resource' => 'Tabla de posición', // Name of the resource being accessed
                        'resource_id' => $teamBPosition->id, // ID of the resource, if applicable
                        'details' => $details, // Any additional details you want to log
                    ]); 
                }
            }
            //teamB Win to W.O.
            elseif ($resultName === 'Ganó B por W.O.') {
                // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
                $teamBPosition = PositionTable::where('team_id', $teamBId)
                ->where('tournament_id', $tournamentId)
                ->first();

                // Incrementar los juegos jugados y los juegos empatados para el equipo B
                if($teamBPosition){

                    $teamBPosition->points -= 3;
                    $teamBPosition->games_played -= 1;
                    $teamBPosition->games_won -= 1;
                    $teamBPosition->goals_scored -= 3;
                    $teamBPosition->save();
                    //log
                    $details = 'Id de Equipo: ' . $teamBPosition->team_id. ', Id de Torneo: ' . $teamBPosition->tournament_id 
                        . ', Pts:'.' -3'.', Part Jugados: -1'. ', Part Ganados: -1, Goles a Favor: -3' ;
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Actualizar', // HTTP method used for the request
                        'resource' => 'Tabla de posición', // Name of the resource being accessed
                        'resource_id' => $teamBPosition->id, // ID of the resource, if applicable
                        'details' => $details, // Any additional details you want to log
                    ]);
                }
                // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
                $teamAPosition = PositionTable::where('team_id', $teamAId)
                ->where('tournament_id', $tournamentId)
                ->first();

                // Incrementar los juegos jugados y los juegos empatados para el equipo A 
                if($teamAPosition){

                $teamAPosition->games_played -= 1;
                $teamAPosition->games_lost -= 1;
                $teamAPosition->goals_against -= 3;
                $teamAPosition->save();
                $details = 'Id de Equipo: ' . $teamAPosition->team_id. ', Id de Torneo: ' . $teamAPosition->tournament_id 
                        .', Part Jugados: -1'. ', Part Perdidos: -1'. ', Goles en Contra: -3';
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Actualizar', // HTTP method used for the request
                        'resource' => 'Tabla de posición', // Name of the resource being accessed
                        'resource_id' => $teamAPosition->id, // ID of the resource, if applicable
                        'details' => $details, // Any additional details you want to log
                    ]); 
                }
            }

        }
        if (!$hasOtherGameSchedulingsA) {
            $positionTableTeamA = PositionTable::where('tournament_id', $tournamentId)
            ->where('team_id', $teamAId)->first();
            PositionTable::where('tournament_id', $tournamentId)
                ->where('team_id', $teamAId)
                ->delete();
            //create log postion table
            $details = 'Id de Equipo: ' . $teamAId. ', Id de Torneo: ' . $tournamentId;
                    LogTransaction::create([
                        'user_id' => auth()->id(), // Assuming you have user authentication
                        'action' => 'Eliminar', // HTTP method used for the request
                        'resource' => 'Posición de Equipo', // Name of the resource being accessed
                        'resource_id' => $positionTableTeamA ? $positionTableTeamA->id : null, // ID del recurso, si corresponde
                        'details' => $details, // Any additional details you want to log
                    ]); 
        }

        if (!$hasOtherGameSchedulingsB) {
            $positionTableTeamB = PositionTable::where('tournament_id', $tournamentId)
            ->where('team_id', $teamBId)->first();
            PositionTable::where('tournament_id', $tournamentId)
                ->where('team_id', $teamBId)
                ->delete();
            $details = 'Id de Equipo: ' . $teamBId. ', Id de Torneo: ' . $tournamentId;
                LogTransaction::create([
                    'user_id' => auth()->id(), // Assuming you have user authentication
                    'action' => 'Eliminar', // HTTP method used for the request
                    'resource' => 'Posición de Equipo', // Name of the resource being accessed
                    'resource_id' => $positionTableTeamB ? $positionTableTeamB->id : null, // ID del recurso, si corresponde
                    'details' => $details, // Any additional details you want to log
                ]); 
        }
        //$game_scheduling->teams()->detach();
        
        //Creating log Transaction
        $details = 'Hora: ' . $game_scheduling->time . ', Id de Rol de Partido: ' . $game_scheduling->game_role_id. ', Id de Equipo A: ' . $game_scheduling->team_a_id. ', Id de Equipo B: ' . $game_scheduling->team_b_id;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Eliminar', // HTTP method used for the request
            'resource' => 'Programación de partido', // Name of the resource being accessed
            'resource_id' => $game_scheduling?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  

        $game_scheduling->delete();
        return to_route('game_schedulings.index');
    }
}
