<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameSchedulingResource;
use App\Http\Resources\GameStatisticResource;
use App\Models\Game;
use App\Models\GameScheduling;
use App\Models\GameStatistic;
use App\Models\LogTransaction;
use App\Models\PositionTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $games = Game::with('gameScheduling.teamA','gameScheduling.teamB' , 'gameStatistic','gameScheduling.gameRole.tournament')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  

        if ($request->search) {
            $games->where(function ($query) use ($request) {
                $query->where('games.id', 'like', $request->search)
                    ->orWhere('games.result', 'like', $request->search . '%')
                    ->orWhereHas('gameScheduling.teamA', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search . '%');
                    })
                    ->orWhereHas('gameScheduling.teamB', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search . '%');
                    })
                    ->orWhereHas('gameScheduling.gameRole', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search . '%');
                    });
            });
        }
        $games = $games->get();
        //dd($games->all());
        return Inertia::render('Admin/Games/GameIndex', [
            'games' => GameResource::collection($games),
            'search' => $request->search, 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Game::class);

        $gameSchedulings = GameScheduling::with('teamA', 'teamB','gameRole','game')->get();
        $gameStatistics = GameStatistic::all();
        //dd($gameSchedulings);
        return Inertia::render('Admin/Games/Create', [
            'game_scheduling' => GameSchedulingResource::collection($gameSchedulings),
            'game_statistic' => GameStatisticResource::collection($gameStatistics),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GameRequest $request)
    {
        $this->authorize('create', Game::class);
       // dd($request->all());
        $validatedData = $request->validated();
        if ($request->has('game_scheduling')) {
            $game_schedulingId = $request->input('game_scheduling.id');
            $validatedData['game_scheduling_id']= $game_schedulingId;
        } 
        if ($request->has('result')) {
            $validatedData['result']= $request->input('result.name');
        } 
        if ($request->has('goals_team_a')) {
            $validatedData['goals_team_a']= $request->input('goals_team_a');
        } 
        if ($request->has('goals_team_b')) {
            $validatedData['goals_team_b']= $request->input('goals_team_b');
        } 

        //Created Game        
        $game = Game::create($validatedData);  
        //Creating log Transaction
        $details = 'Id de Programación de partido: ' . $game->game_scheduling_id . ', Resultado: ' . $game->result. ', Observación: ' . $game->observation;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Crear', // HTTP method used for the request
            'resource' => 'Partido', // Name of the resource being accessed
            'resource_id' => $game?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  

        //Charged PostionTable
        $game = $game->load('gameScheduling.teamA.positionTables','gameScheduling.teamB.positionTables', 'gameScheduling.gameRole.tournament.positionTables');

        // Procesamiento del resultado del partido
        $resultId = $request->result['id'];
        $teamAWon = $resultId === 1;
        $teamBWon = $resultId === 2;
        $drawn = $resultId === 3;
        // Obtener el ID del equipo A, B y del torneo
        $teamAId = $game->gameScheduling?->team_a_id;
        $teamBId = $game->gameScheduling?->team_b_id;
        $tournamentId = $game->gameScheduling?->gameRole?->tournament_id;
        // Obtener goles de equipo A y B
        $goalsTeamA =$validatedData['goals_team_a'];
        $goalsTeamB =$validatedData['goals_team_b'];

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
            $winningTeamPosition->points += 3;
            $winningTeamPosition->games_played += 1;
            $winningTeamPosition->games_won += 1;
            $winningTeamPosition->goals_scored += $goalsWinningTeam;
            $winningTeamPosition->goals_against += $goalsLosingTeam;
            $winningTeamPosition->save();

            //encontrar el registro de positionTable relacionado con el equipo perdedor y el torneo
            $losingTeamPosition = PositionTable::where('team_id', $losingTeamId)
            ->where('tournament_id', $tournamentId)
            ->first();
            $losingTeamPosition->games_played += 1;
            $losingTeamPosition->games_lost += 1;
            $losingTeamPosition->goals_scored += $goalsLosingTeam;
            $losingTeamPosition->goals_against += $goalsWinningTeam;
            // Actualiza los demás campos según la lógica de tu aplicación
            $losingTeamPosition->save();

           //dd($game);
        }
        //Empate
        elseif ($drawn) {
           //aqui hacer los procesos cuando teamB gana

            // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
            $teamAPosition = PositionTable::where('team_id', $teamAId)
                ->where('tournament_id', $tournamentId)
                ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo A
            $teamAPosition->points += 1;
            $teamAPosition->games_played += 1;
            $teamAPosition->games_drawn += 1;
            $teamAPosition->goals_scored += $goalsTeamA;
            $teamAPosition->goals_against += $goalsTeamB;
            $teamAPosition->save();

            // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
            $teamBPosition = PositionTable::where('team_id', $teamBId)
                ->where('tournament_id', $tournamentId)
                ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            $teamBPosition->points += 1;
            $teamBPosition->games_played += 1;
            $teamBPosition->games_drawn += 1;
            $teamBPosition->goals_scored += $goalsTeamB;
            $teamBPosition->goals_against += $goalsTeamA;
            $teamBPosition->save();
        }

        //teamA Win to W.O.
        if ($request->result['id'] === 4) {
            
            $validatedData['goals_team_a'] = 3;
            $validatedData['goals_team_b'] = 0;
             // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
             $teamAPosition = PositionTable::where('team_id', $teamAId)
             ->where('tournament_id', $tournamentId)
             ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo A
            $teamAPosition->points += 3;
            $teamAPosition->games_played += 1;
            $teamAPosition->games_won += 1;
            $teamAPosition->goals_scored += 3;
            $teamAPosition->save();
            // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
            $teamBPosition = PositionTable::where('team_id', $teamBId)
                ->where('tournament_id', $tournamentId)
                ->first();
            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            $teamBPosition->games_played += 1;            
            $teamBPosition->save();
        }
        //teamB Win to W.O.
        elseif ($request->result['id'] === 5) {
            $validatedData['goals_team_a'] = 0;
            $validatedData['goals_team_b'] = 3;
             // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
             $teamBPosition = PositionTable::where('team_id', $teamBId)
             ->where('tournament_id', $tournamentId)
             ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            $teamBPosition->points += 3;
            $teamBPosition->games_played += 1;
            $teamBPosition->games_won += 1;
            $teamBPosition->goals_scored += 3;
            $teamBPosition->save();
            // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
            $teamAPosition = PositionTable::where('team_id', $teamAId)
            ->where('tournament_id', $tournamentId)
            ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo A 
            $teamAPosition->games_played += 1;
            $teamAPosition->save();
        }
        //Canceled
        elseif ($request->result['id'] === 6) {
            $validatedData['goals_team_a'] = 0;
            $validatedData['goals_team_b'] = 0;
        }
        $game->gameStatistic()->create([
            'goals_team_a' => $validatedData['goals_team_a'],
            'goals_team_b' => $validatedData['goals_team_b'],
            'yellow_cards_a' => 0,
            'yellow_cards_b' => 0,
            'red_cards_a' => 0,
            'red_cards_b' => 0,
        ]);
        $details = 'Goles del Equipo A: ' . $game->gameStatistic->goals_team_a . ', Goles del Equipo B: ' . $game->gameStatistic->goals_team_b.', Id del Partido: '.$game->id;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Crear', // HTTP method used for the request
            'resource' => 'Estadisticas del partido', // Name of the resource being accessed
            'resource_id' => $game?->gameStatistic?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
        //*Programare la creacion de registros en la tabla tablePositions
        return redirect()->route('games.index')->with('success', 'Partido creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game): Response
    {
        $game->load('gameScheduling.teamA', 'gameScheduling.teamB', 'gameStatistic','gameScheduling.gameRole');

      /*   $gameScheduling = $game->gameScheduling;
        $gameStatistic = $game->gameStatistic; */
//        dd($photogame);

        return Inertia::render('Admin/Games/Show', [
            'game' => new gameResource($game),
           /*  'game_scheduling' => $gameScheduling,
            'game_statistic' => $gameStatistic, */
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game): Response
    {    
        $this->authorize('update', $game);

        $game->load('gameScheduling.teamA', 'gameScheduling.teamB', 'gameStatistic','gameScheduling.gameRole');
        $gameSchedulings = GameScheduling::with('teamA', 'teamB','gameRole','game')->get();
        //dd($game, $gameSchedulings);
        return Inertia::render('Admin/Games/Edit', [
            'game' => new GameResource($game),
            'gameScheduling' => GameSchedulingResource::collection($gameSchedulings),
            'game_statistic' => GameStatisticResource::collection(GameStatistic::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GameRequest $request, string $id): RedirectResponse
    {                
        $game = Game::find($id);
        $this->authorize('update', $game);

        if (!$game) {
            return redirect()->back()->withErrors(['error' => 'Partido no encontrado.']);
        }

        $validatedData = $request->validated();
        if ($request->has('game_scheduling')) {
            $validatedData['game_scheduling_id']= $request->input('game_scheduling.id');
        } 
        if ($request->has('result')) {
            $validatedData['result']= $request->input('result.name');
        } 
        //dd($request->input('game_statistic.goals_team_a'));
        //dd($validatedData,$request->all());
        if ($request->result['id'] === 4) {
            $validatedData['goals_team_a'] = 3;
            $validatedData['goals_team_b'] = 0;
        } elseif ($request->result['id'] === 5) {
            $validatedData['goals_team_a'] = 0;
            $validatedData['goals_team_b'] = 3;
        } elseif ($request->result['id'] === 6) {
            $validatedData['goals_team_a'] = 0;
            $validatedData['goals_team_b'] = 0;
        }
        //Old Data
        
        $oldGameSchedulingId = $game->game_scheduling_id;
        $oldResult = $game->result;
        $oldObservation = $game->observation;

        //Quitar goles puntos, y demas a los equipos en la tabla de posiciones
             //Charged PostionTable
        $game = $game->load('gameScheduling.teamA.positionTables','gameScheduling.teamB.positionTables', 'gameScheduling.gameRole.tournament.positionTables');

        // Procesamiento del resultado del partido
        $resultName = $game->result;
        $teamAWon = $resultName === 'Ganó A';
        $teamBWon = $resultName === 'Ganó B';
        $drawn = $resultName === 'Empate';
        // Obtener el ID del equipo A, B y del torneo
        $teamAId = $game->gameScheduling?->team_a_id;
        $teamBId = $game->gameScheduling?->team_b_id;
        $tournamentId = $game->gameScheduling?->gameRole?->tournament_id;
        // Obtener goles de equipo A y B
        $goalsTeamA =$game->gameStatistic?->goals_team_a;
        $goalsTeamB =$game->gameStatistic?->goals_team_b;

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
            $winningTeamPosition->points -= 3;
            $winningTeamPosition->games_played -= 1;
            $winningTeamPosition->games_won -= 1;
            $winningTeamPosition->goals_scored -= $goalsWinningTeam;
            $winningTeamPosition->goals_against -= $goalsLosingTeam;
            $winningTeamPosition->save();

            //encontrar el registro de positionTable relacionado con el equipo perdedor y el torneo
            $losingTeamPosition = PositionTable::where('team_id', $losingTeamId)
            ->where('tournament_id', $tournamentId)
            ->first();
            $losingTeamPosition->games_played -= 1;
            $losingTeamPosition->games_lost -= 1;
            $losingTeamPosition->goals_scored -= $goalsLosingTeam;
            $losingTeamPosition->goals_against -= $goalsWinningTeam;
            // Actualiza los demás campos según la lógica de tu aplicación
            $losingTeamPosition->save();

           //dd($game);
        }
        //Empate
        elseif ($drawn) {
           //aqui hacer los procesos cuando teamB gana

            // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
            $teamAPosition = PositionTable::where('team_id', $teamAId)
                ->where('tournament_id', $tournamentId)
                ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo A
            $teamAPosition->points -= 1;
            $teamAPosition->games_played -= 1;
            $teamAPosition->games_drawn -= 1;
            $teamAPosition->goals_scored -= $goalsTeamA;
            $teamAPosition->goals_against -= $goalsTeamB;
            $teamAPosition->save();

            // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
            $teamBPosition = PositionTable::where('team_id', $teamBId)
                ->where('tournament_id', $tournamentId)
                ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            $teamBPosition->points -= 1;
            $teamBPosition->games_played -= 1;
            $teamBPosition->games_drawn -= 1;
            $teamBPosition->goals_scored -= $goalsTeamB;
            $teamBPosition->goals_against -= $goalsTeamA;
            $teamBPosition->save();
        }

        //teamA Win to W.O.
        if ($resultName === 'Ganó A por W.O.') {            
             // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
             $teamAPosition = PositionTable::where('team_id', $teamAId)
             ->where('tournament_id', $tournamentId)
             ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo A
            $teamAPosition->points -= 3;
            $teamAPosition->games_played -= 1;
            $teamAPosition->games_won -= 1;
            $teamAPosition->goals_scored -= 3;
            $teamAPosition->save();
            // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
            $teamBPosition = PositionTable::where('team_id', $teamBId)
                ->where('tournament_id', $tournamentId)
                ->first();
            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            $teamBPosition->games_played -= 1;            
            $teamBPosition->save();
        }
        //teamB Win to W.O.
        elseif ($resultName === 'Ganó B por W.O.') {
             // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
             $teamBPosition = PositionTable::where('team_id', $teamBId)
             ->where('tournament_id', $tournamentId)
             ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            $teamBPosition->points -= 3;
            $teamBPosition->games_played -= 1;
            $teamBPosition->games_won -= 1;
            $teamBPosition->goals_scored -= 3;
            $teamBPosition->save();
            // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
            $teamAPosition = PositionTable::where('team_id', $teamAId)
            ->where('tournament_id', $tournamentId)
            ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo A 
            $teamAPosition->games_played -= 1;
            $teamAPosition->save();
        }

        //dd($oldResult, $oldGoalsTeamA, $oldGoalsTeamB);
        $game->update($validatedData); 
        
        //log Transaction to GAme

        $details = '[Id de Programación de partido: ' . $oldGameSchedulingId . '. a:'.  $game->game_scheduling_id. '], [Resultado: '. $oldResult. '. a:' . $game->result. '], [Observación: '. $oldObservation. '. a:' . $game->observation . ']';
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Actualizar', // HTTP method used for the request
            'resource' => 'Partido', // Name of the resource being accessed
            'resource_id' => $game?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]); 

            //Charged PostionTable
        $game = $game->load('gameScheduling.teamA.positionTables','gameScheduling.teamB.positionTables', 'gameScheduling.gameRole.tournament.positionTables');

        // Procesamiento del resultado del partido
        $resultId = $request->result['id'];
        $teamAWon = $resultId === 1;
        $teamBWon = $resultId === 2;
        $drawn = $resultId === 3;
        // Obtener el ID del equipo A, B y del torneo
        $teamAId = $game->gameScheduling?->team_a_id;
        $teamBId = $game->gameScheduling?->team_b_id;
        $tournamentId = $game->gameScheduling?->gameRole?->tournament_id;
        // Obtener goles de equipo A y B
        $goalsTeamA =$validatedData['goals_team_a'];
        $goalsTeamB =$validatedData['goals_team_b'];

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
            $winningTeamPosition->points += 3;
            $winningTeamPosition->games_played += 1;
            $winningTeamPosition->games_won += 1;
            $winningTeamPosition->goals_scored += $goalsWinningTeam;
            $winningTeamPosition->goals_against += $goalsLosingTeam;
            $winningTeamPosition->save();

            //encontrar el registro de positionTable relacionado con el equipo perdedor y el torneo
            $losingTeamPosition = PositionTable::where('team_id', $losingTeamId)
            ->where('tournament_id', $tournamentId)
            ->first();
            $losingTeamPosition->games_played += 1;
            $losingTeamPosition->games_lost += 1;
            $losingTeamPosition->goals_scored += $goalsLosingTeam;
            $losingTeamPosition->goals_against += $goalsWinningTeam;
            // Actualiza los demás campos según la lógica de tu aplicación
            $losingTeamPosition->save();

           //dd($game);
        }
        //Empate
        elseif ($drawn) {
           //aqui hacer los procesos cuando teamB gana

            // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
            $teamAPosition = PositionTable::where('team_id', $teamAId)
                ->where('tournament_id', $tournamentId)
                ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo A
            $teamAPosition->points += 1;
            $teamAPosition->games_played += 1;
            $teamAPosition->games_drawn += 1;
            $teamAPosition->goals_scored += $goalsTeamA;
            $teamAPosition->goals_against += $goalsTeamB;
            $teamAPosition->save();

            // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
            $teamBPosition = PositionTable::where('team_id', $teamBId)
                ->where('tournament_id', $tournamentId)
                ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            $teamBPosition->points += 1;
            $teamBPosition->games_played += 1;
            $teamBPosition->games_drawn += 1;
            $teamBPosition->goals_scored += $goalsTeamB;
            $teamBPosition->goals_against += $goalsTeamA;
            $teamBPosition->save();
        }

        //teamA Win to W.O.
        if ($request->result['id'] === 4) {            
             // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
             $teamAPosition = PositionTable::where('team_id', $teamAId)
             ->where('tournament_id', $tournamentId)
             ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo A
            $teamAPosition->points += 3;
            $teamAPosition->games_played += 1;
            $teamAPosition->games_won += 1;
            $teamAPosition->goals_scored += 3;
            $teamAPosition->save();
            // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
            $teamBPosition = PositionTable::where('team_id', $teamBId)
                ->where('tournament_id', $tournamentId)
                ->first();
            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            $teamBPosition->games_played += 1;            
            $teamBPosition->save();
        }
        //teamB Win to W.O.
        elseif ($request->result['id'] === 5) {
             // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
             $teamBPosition = PositionTable::where('team_id', $teamBId)
             ->where('tournament_id', $tournamentId)
             ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            $teamBPosition->points += 3;
            $teamBPosition->games_played += 1;
            $teamBPosition->games_won += 1;
            $teamBPosition->goals_scored += 3;
            $teamBPosition->save();
            // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
            $teamAPosition = PositionTable::where('team_id', $teamAId)
            ->where('tournament_id', $tournamentId)
            ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo A 
            $teamAPosition->games_played += 1;
            $teamAPosition->save();
        }

       //Created GameSatatistic
        if ($game->gameStatistic) {
            $game->gameStatistic()->update([
                'goals_team_a' => $validatedData['goals_team_a'],
                'goals_team_b' => $validatedData['goals_team_b'],
            ]);
        } else {
            $game->gameStatistic()->create([
                'goals_team_a' => $validatedData['goals_team_a'],
                'goals_team_b' => $validatedData['goals_team_b'],
                'yellow_cards_a' => 0,
                'yellow_cards_b' => 0,
                'red_cards_a' => 0,
                'red_cards_b' => 0,
            ]);
        }   

        //Log Transaction to gameStatistic
        $details = '[Id del Partido: '.$game->id.'], Goles del Equipo [A, B]: [' . $game->gameStatistic?->goals_team_a . ', ' . $game->gameStatistic?->goals_team_b. '], Amarillas del Equipo [A, B]: [' . $game->gameStatistic?->yellow_cards_a.', '.$game->gameStatistic?->yellow_cards_b.'], '. ' Rojas del Equipo [A, B]: [' . $game->gameStatistic?->red_cards_a.', '.$game->gameStatistic?->red_cards_b.']';
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Actualizar', // HTTP method used for the request
            'resource' => 'Estadisticas del partido', // Name of the resource being accessed
            'resource_id' => $game?->gameStatistic?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
        
        return redirect()->route('games.index')->with('success', 'Partido actualizada correctamente');        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game): RedirectResponse
    {       
        $this->authorize('delete', $game);
        //Creating log Transaction
        $game = $game->load('gameStatistic');
        //dd($game);

        $details = 'Id de Programación de partido: ' . $game->game_scheduling_id . ', Resultado: ' . $game->result. ', Observación: ' . $game->observation;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Eliminar', // HTTP method used for the request
            'resource' => 'Partido', // Name of the resource being accessed
            'resource_id' => $game?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]); 
        $details = 'Id del Partido: '.$game->id.', Goles del Equipo [A, B]: [' . $game->gameStatistic?->goals_team_a . ', ' . $game->gameStatistic?->goals_team_b. '], Amarillas del Equipo [A, B]: [' . $game->gameStatistic?->yellow_cards_a.', '.$game->gameStatistic?->yellow_cards_b.'], '. '], Rojas del Equipo [A, B]: [' . $game->gameStatistic?->red_cards_a.', '.$game->gameStatistic?->red_cards_b.']';
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Eliminar', // HTTP method used for the request
            'resource' => 'Estadisticas del partido', // Name of the resource being accessed
            'resource_id' => $game?->gameStatistic?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  

        //Quitar goles puntos, y demas a los equipos en la tabla de posiciones
             //Charged PostionTable
        $game = $game->load('gameScheduling.teamA.positionTables','gameScheduling.teamB.positionTables', 'gameScheduling.gameRole.tournament.positionTables');

        // Procesamiento del resultado del partido
        $resultName = $game->result;
        $teamAWon = $resultName === 'Ganó A';
        $teamBWon = $resultName === 'Ganó B';
        $drawn = $resultName === 'Empate';
        // Obtener el ID del equipo A, B y del torneo
        $teamAId = $game->gameScheduling?->team_a_id;
        $teamBId = $game->gameScheduling?->team_b_id;
        $tournamentId = $game->gameScheduling?->gameRole?->tournament_id;
        // Obtener goles de equipo A y B
        $goalsTeamA =$game->gameStatistic?->goals_team_a;
        $goalsTeamB =$game->gameStatistic?->goals_team_b;

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

            // Incrementar los juegos jugados y los juegos empatados para el equipo A
            if($teamAPosition){
            $teamAPosition->points -= 1;
            $teamAPosition->games_played -= 1;
            $teamAPosition->games_drawn -= 1;
            $teamAPosition->goals_scored -= $goalsTeamA;
            $teamAPosition->goals_against -= $goalsTeamB;
            $teamAPosition->save();
            }
            // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
            $teamBPosition = PositionTable::where('team_id', $teamBId)
                ->where('tournament_id', $tournamentId)
                ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            if($teamBPosition){
            $teamBPosition->points -= 1;
            $teamBPosition->games_played -= 1;
            $teamBPosition->games_drawn -= 1;
            $teamBPosition->goals_scored -= $goalsTeamB;
            $teamBPosition->goals_against -= $goalsTeamA;
            $teamBPosition->save();
            }
        }

        //teamA Win to W.O.
        if ($resultName === 'Ganó A por W.O.') {            
             // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
             $teamAPosition = PositionTable::where('team_id', $teamAId)
             ->where('tournament_id', $tournamentId)
             ->first();

            // Incrementar los juegos jugados y los juegos empatados para el equipo A
            if($teamAPosition){
            $teamAPosition->points -= 3;
            $teamAPosition->games_played -= 1;
            $teamAPosition->games_won -= 1;
            $teamAPosition->goals_scored -= 3;
            $teamAPosition->save();
            }
            // Encontrar el registro de PositionTable relacionado con el equipo B y el torneo
            $teamBPosition = PositionTable::where('team_id', $teamBId)
                ->where('tournament_id', $tournamentId)
                ->first();
            // Incrementar los juegos jugados y los juegos empatados para el equipo B
            if($teamBPosition){
            $teamBPosition->games_played -= 1;            
            $teamBPosition->save();
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
            }
            // Encontrar el registro de PositionTable relacionado con el equipo A y el torneo
            $teamAPosition = PositionTable::where('team_id', $teamAId)
            ->where('tournament_id', $tournamentId)
            ->first();
            if($teamAPosition){
            // Incrementar los juegos jugados y los juegos empatados para el equipo A 
            $teamAPosition->games_played -= 1;
            $teamAPosition->save();
            }
        }

        $game->delete();       
        return to_route('games.index');
    }
}
