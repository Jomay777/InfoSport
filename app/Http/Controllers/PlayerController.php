<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PhotoPlayerResource;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\TeamResource;
use App\Models\PhotoPlayer;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
//use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $players = Player::with('team', 'photoPlayer')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  

        if ($request->search) {
            $players->where('players.first_name', 'like', '%' . $request->search . '%')
            ->orWhere('players.second_name', 'like', '%' . $request->search . '%')
            ->orWhere('players.last_name', 'like', '%' . $request->search . '%')
            ->orWhere('players.mother_last_name', 'like', '%' . $request->search . '%');
        }
        $players = $players->get();
        //dd($players->all());
        return Inertia::render('Admin/Players/PlayerIndex', [
            'players' => PlayerResource::collection($players),
            'search' => $request->search, 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Players/Create', [
            'team' => TeamResource::collection(Team::all()),
            'photoPlayer' => PhotoPlayerResource::collection(PhotoPlayer::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->has('team')) {
            $teamId = $request->input('team.id');
            $validatedData['team_id']= $teamId;
        } 
        if ($request->has('state')) {
            $validatedData['state']= $request->input('state.id');
        } 
        if (isset($validatedData['birth_date'])) {
            $validatedData['birth_date'] = Carbon::parse($validatedData['birth_date'])->toDateString();
        }
        Player::create($validatedData);                     
        return redirect()->route('players.index')->with('success', 'Jugador creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player): Response
    {
        
        $team = $player->team;
        $photoPlayer = $player->photoPlayer;
//        dd($photoPlayer);

        return Inertia::render('Admin/Players/Show', [
            'player' => new PlayerResource($player),
            'team' => $team,
            'photoPlayer' => $photoPlayer,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player): Response
    {    
        $player->load('team');
        return Inertia::render('Admin/Players/Edit', [
            'player' => new PlayerResource($player),
            'team' => TeamResource::collection(Team::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerRequest $request, string $id):RedirectResponse
    {        
        $player = Player::find($id);               
        $validatedData = $request->validated();
        if ($request->has('team')) {
            $teamId = $request->input('team.id');
            $validatedData['team_id']= $teamId;
        } 
        if ($request->has('state')) {
            $validatedData['state']= $request->input('state.id');
        } 
        if (isset($validatedData['birth_date'])) {
            $validatedData['birth_date'] = Carbon::parse($validatedData['birth_date'])->toDateString();
        }
        $player->update($validatedData);       
        
        return to_route('players.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player): RedirectResponse
    {
        $player->delete();
        return to_route('players.index');
    }
}
