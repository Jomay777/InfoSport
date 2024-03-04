<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerSanctionRequest;
use App\Http\Resources\GameResource;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\PlayerSanctionResource;
use App\Models\Game;
use App\Models\Player;
use App\Models\PlayerSanction;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use function Pest\Laravel\get;

class PlayerSanctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $player_sanctions = PlayerSanction::with('game.gameScheduling.teams', 'player.team.club','game.gameScheduling.gameRole.tournament')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  

        if ($request->search) {
            $player_sanctions->where(function ($query) use ($request) {
                $query->where('player_sanctions.id', 'like', '%' . $request->search . '%')
                    ->orWhere('player_sanctions.state', 'like', $request->search . '%')
                    ->orWhereHas('game.gameScheduling.teams', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('game.gameScheduling.gameRole', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('player', function ($subQuery) use ($request) {
                        $subQuery->where('first_name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        $player_sanctions = $player_sanctions->get();
        //dd($player_sanctions->all());
        return Inertia::render('Admin/PlayerSanctions/PlayerSanctionIndex', [
            'player_sanctions' => PlayerSanctionResource::collection($player_sanctions),
            'search' => $request->search, 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        //$this->authorize('create', Game::class);

        $games = Game::with('gameScheduling.teams.players','gameScheduling.gameRole.tournament','playerSanctions.player')->get();
        $players = Player::with('team.club')->get();
        //dd($games, $players);
        return Inertia::render('Admin/PlayerSanctions/Create', [
            'games' => GameResource::collection($games),
            'players' => PlayerResource::collection($players),
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerSanctionRequest $request)
    {
        //$this->authorize('create', Game::class);
        //dd(request()->all());
        //dd(PlayerSanction::find(11));
        $validatedData = $request->validated();
        if ($request->has('state')) {
            $validatedData['state']= $request->input('state.name');
        } 
        if ($request->has('games')) {
            $validatedData['game_id']= $request->input('games.id');
        } 
        if ($request->has('players')) {
            $validatedData['player_id']= $request->input('players.id');
        } 
        if ($request->has('yellow_cards') && $request->input('yellow_cards') === '2') {      
            $validatedData['red_card']= '1';
        } 
//   dd($validatedData, $request->all());
        $player_sanction = PlayerSanction::create($validatedData);    
     
        return redirect()->route('player_sanctions.index')->with('success', 'Sanción creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(PlayerSanction $playerSanction): Response
    {
        $player_sanctions = PlayerSanction::with('game.gameScheduling.teams', 'player.team.club','game.gameScheduling.gameRole.tournament');
        $player_sanctions = $player_sanctions->get();

        $playerSanction->load('game.gameScheduling.teams','game.gameScheduling.gameRole.tournament', 'player.team');

        return Inertia::render('Admin/PlayerSanctions/Show', [
            'player_sanction' => new PlayerSanctionResource($playerSanction),
            'player_sanctions' => PlayerSanctionResource::collection($player_sanctions),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlayerSanction $playerSanction): Response
    {    
//        $this->authorize('update', $game);
        
        $playerSanction->load('game.gameScheduling.teams','game.gameScheduling.gameRole.tournament', 'player.team');

        $games = Game::with('gameScheduling.teams.players','gameScheduling.gameRole.tournament','playerSanctions.player')->get();
        $players = Player::with('team.club')->get();
       // dd($playerSanction);
        return Inertia::render('Admin/PlayerSanctions/Edit', [
            'player_sanction' => new PlayerSanctionResource($playerSanction),
            'games' => GameResource::collection($games),
            'players' => PlayerResource::collection($players),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerSanctionRequest $request, string $id): RedirectResponse
    {                
        $player_sanction = PlayerSanction::find($id);
        //$this->authorize('update', $game);
        //dd($player_sanction, $request->all());
        if (!$player_sanction) {
            return redirect()->back()->withErrors(['error' => 'Sanción de jugador no encontrado.']);
        }

        $validatedData = $request->validated();
        if ($request->has('state')) {
            $validatedData['state']= $request->input('state.name');
        } 
        if ($request->has('games')) {
            $validatedData['game_id']= $request->input('games.id');
        } 
        if ($request->has('players')) {
            $validatedData['player_id']= $request->input('players.id');
        } 
        if ($request->has('yellow_cards') && $request->input('yellow_cards') === '2') {      
            $validatedData['red_card']= '1';
        } 
        //dd($request->input('game_statistic.goals_team_a'));
        //dd($validatedData);
        $player_sanction->update($validatedData); 
        
        return redirect()->route('player_sanctions.index')->with('success', 'Partido actualizada correctamente');        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlayerSanction $playerSanction): RedirectResponse
    {       
        //$this->authorize('delete', $game);

        $playerSanction->delete();       
        return to_route('player_sanctions.index');
    }
}
