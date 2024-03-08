<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameSchedulingRequest;
use App\Http\Requests\UpdateGameSchedulingRequest;
use App\Http\Resources\GameRoleResource;
use App\Http\Resources\GameSchedulingResource;
use App\Http\Resources\TeamResource;
use App\Models\GameRole;
use App\Models\GameScheduling;
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
       //dd($teams, $teamA,$teamB, $request->all());
       //
        
        $game_scheduling = GameScheduling::create($validatedData);

        if (!$game_scheduling) {
            // Handle game_scheduling creation errors
            return back()->withErrors(['game_scheduling' => 'Falló la creación de programación de partido.']);
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
        $gameScheduling = GameScheduling::find($id);
        $this->authorize('update', $gameScheduling);


        if (!$gameScheduling) {
            return redirect()->back()->withErrors(['error' => 'Programación de partido no encontrada.']);
        }

        $validatedData = $request->validated();

        if ($request->has('gameRole.id')) {
            $validatedData['game_role_id'] = $request->input('gameRole.id');
        } 
        if ($request->has('teamA.id')) {
            $validatedData['team_a_id'] = $request->input('teamA.id');
        } 
        if ($request->has('teamB.id')) {
            $validatedData['team_b_id'] = $request->input('teamB.id');
        } 
        //dd($validatedData, $request->all());
        $gameScheduling->update($validatedData);

        // Obtener solo los IDs de los equipos
   /*      $newTeamIds = Team::whereIn('name', $request->input('teams.*.name'))->pluck('id')->toArray();

        // Obtener los IDs actuales de los equipos
        $currentTeamIds = $gameScheduling->teams()->pluck('teams.id')->toArray();

            // Verificar si los IDs son los mismos
        if ($newTeamIds != $currentTeamIds) {
            // Los IDs son diferentes, eliminar registros existentes antes de sincronizar los nuevos
            $gameScheduling->teams()->detach();
            // Usar sync para garantizar la relación exacta
            $gameScheduling->teams()->sync($newTeamIds);
        } */
            //dd($validatedData);
        return redirect()->route('game_schedulings.index')->with('success', 'Programación de partido actualizada correctamente');        
    }
     /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameScheduling $game_scheduling): RedirectResponse
    {
        $this->authorize('delete', $game_scheduling);
        
        //$game_scheduling->teams()->detach();
        $game_scheduling->delete();
        return to_route('game_schedulings.index');
    }
}
