<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerSanctionRequest;
use App\Http\Requests\TeamSanctionRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamSanctionResource;
use App\Models\Game;
use App\Models\Team;
use App\Models\TeamSanction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamSanctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $team_sanctions = TeamSanction::with('game.gameScheduling.teams', 'team.club','game.gameScheduling.gameRole.tournament')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  

        if ($request->search) {
            $team_sanctions->where(function ($query) use ($request) {
                $query->where('team_sanctions.id', 'like', $request->search)
                    ->orWhere('team_sanctions.state', 'like', $request->search . '%')
                    ->orWhere('team_sanctions.sanction', 'like', $request->search . '%')
                    ->orWhereHas('game.gameScheduling.teams', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('game.gameScheduling.gameRole', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('game.gameScheduling.gameRole.tournament', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('team', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('team.club', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        $team_sanctions = $team_sanctions->get();
        //dd($team_sanctions->all());
        return Inertia::render('Admin/TeamSanctions/TeamSanctionIndex', [
            'team_sanctions' => TeamSanctionResource::collection($team_sanctions),
            'search' => $request->search, 
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //$this->authorize('create', Game::class);

        $games = Game::with('gameScheduling.teams.players','gameScheduling.gameRole.tournament','teamSanctions.team')->get();
        $teams = Team::with('club', 'gameSchedulings')->get();
        //dd($games, $teams);
        return Inertia::render('Admin/TeamSanctions/Create', [
            'games' => GameResource::collection($games),
            'teams' => TeamResource::collection($teams),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamSanctionRequest $request)
    {
        {
            //$this->authorize('create', Game::class);
            //dd(request()->all());
            $validatedData = $request->validated();
            if ($request->has('state')) {
                $validatedData['state']= $request->input('state.name');
            } 
            if ($request->has('games')) {
                $validatedData['game_id']= $request->input('games.id');
            } 
            if ($request->has('teams')) {
                $validatedData['team_id']= $request->input('teams.id');
            }             
    //   dd($validatedData, $request->all());
            $team_sanction = TeamSanction::create($validatedData);    
         
            return redirect()->route('team_sanctions.index')->with('success', 'Sanción creada correctamente');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamSanction $teamSanction): Response
    {
        //$player_sanctions = PlayerSanction::with('game.gameScheduling.teams', 'player.team.club','game.gameScheduling.gameRole.tournament');
        //$player_sanctions = $player_sanctions->get();

        $teamSanction->load('game.gameScheduling.teams','game.gameScheduling.gameRole.tournament', 'team.club');

        return Inertia::render('Admin/TeamSanctions/Show', [
            'team_sanction' => new TeamSanctionResource($teamSanction)
          //  'player_sanctions' => PlayerSanctionResource::collection($player_sanctions),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TeamSanction $teamSanction): Response
    {    
//        $this->authorize('update', $game);
        
        $teamSanction->load('game.gameScheduling.teams','game.gameScheduling.gameRole.tournament', 'team.club');

        $games = Game::with('gameScheduling.teams.players','gameScheduling.gameRole.tournament','teamSanctions.team')->get();
        $teams = Team::with('club')->get();
       // dd($teamSanction);
        return Inertia::render('Admin/TeamSanctions/Edit', [
            'team_sanction' => new TeamSanctionResource($teamSanction),
            'games' => GameResource::collection($games),
            'teams' => TeamResource::collection($teams),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamSanctionRequest $request, string $id): RedirectResponse
    {                
        $team_sanction = TeamSanction::find($id);
        //$this->authorize('update', $game);
        //dd($team_sanction, $request->all());
        if (!$team_sanction) {
            return redirect()->back()->withErrors(['error' => 'Sanción de equipo no encontrado.']);
        }

        $validatedData = $request->validated();
        if ($request->has('state')) {
            $validatedData['state']= $request->input('state.name');
        } 
        if ($request->has('games')) {
            $validatedData['game_id']= $request->input('games.id');
        } 
        if ($request->has('teams')) {
            $validatedData['team_id']= $request->input('teams.id');
        }
        //dd($request->input('game_statistic.goals_team_a'));
        //dd($validatedData);
        $team_sanction->update($validatedData); 
        
        return redirect()->route('team_sanctions.index')->with('success', 'Sación de equipo actualizada correctamente');        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamSanction $teamSanction): RedirectResponse
    {       
        //$this->authorize('delete', $game);

        $teamSanction->delete();       
        return to_route('team_sanctions.index');
    }
}
