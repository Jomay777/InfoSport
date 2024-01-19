<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameRoleRequest;
use App\Http\Resources\GameRoleResource;
use App\Http\Resources\PitchResource;
use App\Http\Resources\TournamentResource;
use App\Models\GameRole;
use App\Models\Pitch;
use App\Models\Tournament;
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
