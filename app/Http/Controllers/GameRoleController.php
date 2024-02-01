<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameRoleRequest;
use App\Http\Resources\GameRoleResource;
use App\Http\Resources\GameSchedulingResource;
use App\Http\Resources\PitchResource;
use App\Http\Resources\TournamentResource;
use App\Models\GameRole;
use App\Models\GameScheduling;
use App\Models\Pitch;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class GameRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $game_roles = GameRole::with('tournament', 'pitch')
        ->latest()  
        ->take(20); 

        if ($request->search) {
            $game_roles->where('game_roles.name', 'like', '%' . $request->search . '%');
        }

        $game_roles = $game_roles->get();

        return Inertia::render('Admin/GameRoles/GameRoleIndex', [
            'game_roles' => GameRoleResource::collection($game_roles),
            'search' => $request->search, // Pasa el valor de búsqueda a la vista
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', GameRole::class);

        return Inertia::render('Admin/GameRoles/Create', [
            'tournament' => TournamentResource::collection(Tournament::all()),
            'pitch' => PitchResource::collection(Pitch::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GameRoleRequest $request)
    {
        $this->authorize('create', GameRole::class);

        $validatedData = $request->validated();

        if ($request->has('tournament')) {
            $validatedData['tournament_id'] = $request->input('tournament.id');
        } 
        if ($request->has('pitch')) {
            $validatedData['pitch_id'] = $request->input('pitch.id');
        } 
        if (isset($validatedData['date'])) {
            $validatedData['date'] = Carbon::parse($validatedData['date'])->toDateString();
        }
       
        GameRole::create($validatedData);

               
        return redirect()->route('game_roles.index')->with('success', 'Torneo creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(GameRole $game_role): Response
    {
        $game_role->load('gameSchedulings.teams', 'tournament', 'pitch');

        //dd($game_role);
        return Inertia::render('Admin/GameRoles/Show', [
            'game_role' => new GameRoleResource($game_role),
            'game_scheduling' => GameSchedulingResource::collection(GameScheduling::all()),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GameRole $game_role): Response
    {    
        $this->authorize('update', $game_role);

        $game_role->load('tournament','pitch');
        return Inertia::render('Admin/GameRoles/Edit', [
            'game_role' => new GameRoleResource($game_role),
            'tournament' => TournamentResource::collection(Tournament::all()),
            'pitch' => PitchResource::collection(Pitch::all())
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GameRoleRequest $request, string $id):RedirectResponse
    {        
        $game_role = GameRole::find($id);   
        $this->authorize('update', $game_role);
            
        $validatedData = $request->validated();
        if ($request->has('tournament')) {
            $validatedData['tournament_id'] = $request->input('tournament.id');
        } 
        if ($request->has('pitch')) {
            $validatedData['pitch_id'] = $request->input('pitch.id');
        } 
        if (isset($validatedData['date'])) {
            $validatedData['date'] = Carbon::parse($validatedData['date'])->toDateString();
        }
       //dd($validatedData);
        $game_role->update($validatedData);     
        return to_route('game_roles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameRole $game_role): RedirectResponse
    {
        $this->authorize('delete', $game_role);

        $game_role->delete();
        return to_route('game_roles.index');
    }
    /**
     * Publish GameRole
     */
    public function publish(GameRole $game_role){
        //dd($game_role);
        $game_role->touch();
        return to_route('dashboard');
    }
}
