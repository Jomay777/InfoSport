<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameSchedulingResource;
use App\Http\Resources\GameStatisticResource;
use App\Models\Game;
use App\Models\GameScheduling;
use App\Models\GameStatistic;
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
        $games = Game::with('gameScheduling.teams', 'gameStatistic','gameScheduling.gameRole')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  

        if ($request->search) {
            $games->where(function ($query) use ($request) {
                $query->where('games.id', 'like', '%' . $request->search . '%')
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
        
        $validatedData = $request->validated();
        if ($request->has('game_scheduling')) {
            $game_schedulingId = $request->input('game_scheduling.id');
            $validatedData['game_scheduling_id']= $game_schedulingId;
        } 
        //dd($request->input('game_statistic.goals_team_a'));

        $game = Game::create($validatedData);    
        $game->gameStatistic()->create([
            'goals_team_a' => $request->input('game_statistic.goals_team_a'),
            'goals_team_b' => $request->input('game_statistic.goals_team_b'),
            'yellow_cards_a' => $request->input('game_statistic.yellow_cards_a'),
            'yellow_cards_b' => $request->input('game_statistic.yellow_cards_b'),
            'red_cards_a' => $request->input('game_statistic.red_cards_a'),
            'red_cards_b' => $request->input('game_statistic.red_cards_b'),
        ]);
        return redirect()->route('games.index')->with('success', 'Partido creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
