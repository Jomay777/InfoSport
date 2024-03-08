<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameSchedulingResource;
use App\Http\Resources\GameStatisticResource;
use App\Http\Resources\TeamResource;
use App\Models\Game;
use App\Models\GameScheduling;
use App\Models\GameStatistic;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\Console\Input\Input;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $games = Game::with('gameScheduling.teams', 'gameStatistic','gameScheduling.gameRole.tournament')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  

        if ($request->search) {
            $games->where(function ($query) use ($request) {
                $query->where('games.id', 'like', '%' . $request->search . '%')
                    ->orWhere('games.result', 'like', '%' . $request->search . '%')
                    ->orWhereHas('gameScheduling.teams', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('gameScheduling.gameRole', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
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

        $gameSchedulings = GameScheduling::with('teams','gameRole','game')->get();
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
        if ($request->has('game_statistic')) {
            $validatedData['goals_team_a']= $request->input('game_statistic.goals_team_a');
        } 
        if ($request->has('game_statistic.goals_team_b')) {
            $validatedData['goals_team_b']= $request->input('game_statistic.goals_team_b');
        } 
        //recatando datos
        $gameScheduling = GameScheduling::with('teams')->find($validatedData['game_scheduling_id']);
        $teams = $gameScheduling->teams;
        if (count($teams) >= 2) {
            $teamA = $teams[0];
            $teamB = $teams[1];
        } else {error_log('Equipos no asignados');}

        //dd($request->input('game_statistic.goals_team_a'));
        //dd($request->all(), $validatedData);
        dd($validatedData, $request->all(), $gameScheduling,$teams, $teamA, $teamB);
        $game = Game::create($validatedData);    
        $game->gameStatistic()->create([
            'goals_team_a' => $request->input('game_statistic.goals_team_a'),
            'goals_team_b' => $request->input('game_statistic.goals_team_b'),
            'yellow_cards_a' => 0,
            'yellow_cards_b' => 0,
            'red_cards_a' => 0,
            'red_cards_b' => 0,
        ]);
        //*Programare la creacion de registros en la tabla tablePasitions
        return redirect()->route('games.index')->with('success', 'Partido creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game): Response
    {
        $game->load('gameScheduling.teams', 'gameStatistic','gameScheduling.gameRole');

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

        $game->load('gameScheduling.teams', 'gameStatistic','gameScheduling.gameRole');
        $gameSchedulings = GameScheduling::with('teams','gameRole','game')->get();

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
        //dd($validatedData);
        $game->update($validatedData); 
        if ($game->gameStatistic) {
            $game->gameStatistic()->update([
                'goals_team_a' => $request->input('game_statistic.goals_team_a'),
                'goals_team_b' => $request->input('game_statistic.goals_team_b'),
            ]);
        } else {
            $game->gameStatistic()->create([
                'goals_team_a' => $request->input('game_statistic.goals_team_a'),
                'goals_team_b' => $request->input('game_statistic.goals_team_b'),
                'yellow_cards_a' => 0,
                'yellow_cards_b' => 0,
                'red_cards_a' => 0,
                'red_cards_b' => 0,
            ]);
        }   
        
        return redirect()->route('games.index')->with('success', 'Partido actualizada correctamente');        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game): RedirectResponse
    {       
        $this->authorize('delete', $game);

        $game->delete();       
        return to_route('games.index');
    }
}
