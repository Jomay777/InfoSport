<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameSchedulingRequest;
use App\Http\Resources\GameRoleResource;
use App\Http\Resources\GameSchedulingResource;
use App\Http\Resources\TeamResource;
use App\Models\GameRole;
use App\Models\GameScheduling;
use App\Models\Team;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class GameSchedulingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $game_schedulings = GameScheduling::with('teams', 'gameRole')
            ->latest()
            ->take(20);
    
        if ($request->search) {
            $game_schedulings->where(function ($query) use ($request) {
                $query->where('game_schedulings.id', 'like', '%' . $request->search . '%')
                    ->orWhere('game_schedulings.time', 'like', '%' . $request->search . '%')
                    ->orWhereHas('gameRole', function ($subQuery) use ($request) {
                        $subQuery->where('game_roles.name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('teams', function ($subQuery) use ($request) {
                        $subQuery->where('teams.name', 'like', '%' . $request->search . '%');
                    });
            });
        }
    
        $game_schedulings = $game_schedulings->get();
    
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
        return Inertia::render('Admin/GameSchedulings/Create', [
            'teams' => TeamResource::collection(Team::all()),
            'game_role' => GameRoleResource::collection(GameRole::all())
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(GameSchedulingRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->has('game_role')) {
            $validatedData['game_role_id'] = $request->input('game_role.id');
        } 
       
        $game_scheduling = GameScheduling::create($validatedData);

        if (!$game_scheduling) {
            // Handle game_scheduling creation errors
            return back()->withErrors(['game_scheduling' => 'Falló la creación de programación de partido.']);
        }
        $teams = Team::whereIn('name', $request->input('teams.*.name'))->get();
        // Obtener solo los IDs de los equipos
        $teamIds = $teams->pluck('id')->toArray();
        $game_scheduling->teams()->sync($teamIds);

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
        $game_scheduling->load('teams','gameRole');
 
        return Inertia::render('Admin/GameSchedulings/Edit', [
            'game_scheduling' => new GameSchedulingResource($game_scheduling),
            'teams' => TeamResource::collection(Team::all()),
            'game_role' => GameRoleResource::collection(GameRole::all())
        ]);
    }
      /**
     * Update the specified resource in storage.
     */
    public function update(GameSchedulingRequest $request, string $id): RedirectResponse
    {                
        $gameScheduling = GameScheduling::find($id);

        if (!$gameScheduling) {
            return redirect()->back()->withErrors(['error' => 'Programación de partido no encontrada.']);
        }

        $validatedData = $request->validated();

        if ($request->has('game_role.id')) {
            $validatedData['game_role_id'] = $request->input('game_role.id');
        } 

        $gameScheduling->update($validatedData);

        // Obtener solo los IDs de los equipos
        $newTeamIds = Team::whereIn('name', $request->input('teams.*.name'))->pluck('id')->toArray();

        // Obtener los IDs actuales de los equipos
        $currentTeamIds = $gameScheduling->teams()->pluck('teams.id')->toArray();

            // Verificar si los IDs son los mismos
        if ($newTeamIds != $currentTeamIds) {
            // Los IDs son diferentes, eliminar registros existentes antes de sincronizar los nuevos
            $gameScheduling->teams()->detach();
            // Usar sync para garantizar la relación exacta
            $gameScheduling->teams()->sync($newTeamIds);
        }
            //dd($validatedData);
        return redirect()->route('game_schedulings.index')->with('success', 'Programación de partido actualizada correctamente');        
    }
     /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameScheduling $game_scheduling): RedirectResponse
    {
        
        $game_scheduling->teams()->detach();
        $game_scheduling->delete();
        return to_route('game_schedulings.index');
    }
}
