<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamSanctionRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamSanctionResource;
use App\Models\Game;
use App\Models\LogTransaction;
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
        $team_sanctions = TeamSanction::with('game.gameScheduling.teamA', 'game.gameScheduling.teamB', 'team.club','game.gameScheduling.gameRole.tournament')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  

        if ($request->search) {
            $team_sanctions->where(function ($query) use ($request) {
                $query->where('team_sanctions.id', 'like', $request->search)
                    ->orWhere('team_sanctions.state', 'like', $request->search . '%')
                    ->orWhere('team_sanctions.sanction', 'like', $request->search . '%')
                    ->orWhereHas('game.gameScheduling.teamA', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search . '%');
                    })
                    ->orWhereHas('game.gameScheduling.teamB', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search . '%');
                    })
                    ->orWhereHas('game.gameScheduling.gameRole', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search);
                    })
                    ->orWhereHas('game.gameScheduling.gameRole.tournament', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search . '%');
                    })
                    ->orWhereHas('team', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search . '%');
                    })
                    ->orWhereHas('team.club', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', $request->search . '%');
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

        $games = Game::with('gameScheduling.teamA.players', 'gameScheduling.teamB.players','gameScheduling.gameRole.tournament','teamSanctions.team')->get();
        $teams = Team::with('club', 'gameSchedulingsAsTeamA', 'gameSchedulingsAsTeamB')->get();
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
            //Created LogTransaction

        $details = 'Sanción: ' . $team_sanction->sanction .', Estado: ' . $team_sanction->state . ', Id del Equipo: ' . $team_sanction->team_id. ', Id de Partido: ' . $team_sanction->game_id. ', Observación: ' . $team_sanction->observation;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Crear', // HTTP method used for the request
            'resource' => 'Sanción de Equipo', // Name of the resource being accessed
            'resource_id' => $team_sanction?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
            return redirect()->route('team_sanctions.index')->with('success', 'Sanción creada correctamente');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(TeamSanction $teamSanction): Response
    {
        //$player_sanctions = PlayerSanction::with('game.gameScheduling.teams', 'player.team.club','game.gameScheduling.gameRole.tournament');
        //$player_sanctions = $player_sanctions->get();

        $teamSanction->load('game.gameScheduling.teamA', 'game.gameScheduling.teamB', 'game.gameScheduling.gameRole.tournament', 'team.club');

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
        
        $teamSanction->load('game.gameScheduling.teamA', 'game.gameScheduling.teamB', 'game.gameScheduling.gameRole.tournament', 'team.club');

        $games = Game::with('gameScheduling.teamA.players', 'gameScheduling.teamB.players', 'gameScheduling.gameRole.tournament','teamSanctions.team')->get();
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
        //Old Data
        $oldSanction = $team_sanction->sanction;
        $oldState = $team_sanction->state ;
        $oldTeamId = $team_sanction->team_id;
        $oldGameId = $team_sanction->game_id;
        $oldObservation = $team_sanction->observation;
        $team_sanction->update($validatedData); 

        //Created LogTransaction

        $details = '[Sanción: ' . $oldSanction . '. a: ' . $team_sanction->sanction . '], [Estado: ' . $oldState . '. a: ' . $team_sanction->state . '], [Id del Equipo: ' . $oldTeamId . '. a: ' . $team_sanction->team_id . '], [Id de Partido: ' . $oldGameId . '. a: ' . $team_sanction->game_id . '], [Observación: ' . $oldObservation . '. a: ' . $team_sanction->observation . ']';
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Actualizar', // HTTP method used for the request
            'resource' => 'Sanción de Equipo', // Name of the resource being accessed
            'resource_id' => $team_sanction?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
        return redirect()->route('team_sanctions.index')->with('success', 'Sación de equipo actualizada correctamente');        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TeamSanction $teamSanction): RedirectResponse
    {       
        //$this->authorize('delete', $game);
        $details = 'Sanción: ' . $teamSanction->sanction .', Estado: ' . $teamSanction->state . ', Id del Equipo: ' . $teamSanction->team_id. ', Id de Partido: ' . $teamSanction->game_id. ', Observación: ' . $teamSanction->observation;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Eliminar', // HTTP method used for the request
            'resource' => 'Sanción de Equipo', // Name of the resource being accessed
            'resource_id' => $teamSanction?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
        $teamSanction->delete();       
        return to_route('team_sanctions.index');
    }
}
