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
use function PHPSTORM_META\map;

class PlayerSanctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $player_sanctions = PlayerSanction::with('game.gameScheduling.teamA', 'game.gameScheduling.teamB', 'player.team.club','game.gameScheduling.gameRole.tournament')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  

        if ($request->search) {
            $player_sanctions->where(function ($query) use ($request) {
                $query->where('player_sanctions.id', 'like', '%' . $request->search . '%')
                    ->orWhere('player_sanctions.state', 'like', $request->search . '%')
                    ->orWhereHas('game.gameScheduling.teamA', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('game.gameScheduling.teamB', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('game.gameScheduling.gameRole', function ($subQuery) use ($request) {
                        $subQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('game.gameScheduling.gameRole.tournament', function ($subQuery) use ($request) {
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

        $games = Game::with('gameScheduling.teamA.players', 'gameScheduling.teamB.players', 'gameScheduling.gameRole.tournament','playerSanctions.player', 'gameStatistic')->get();
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
//  dd($validatedData, $request->all());
        $player_sanction = PlayerSanction::create($validatedData);    
        //dd($player_sanction->player->team, $player_sanction->game->gameScheduling->teams);
        //dd($player_sanction->game->gameScheduling->teams);
        //$teams = $player_sanction->game->gameScheduling->teamA;

        
        $teamA = $player_sanction->game->gameScheduling->teamA;
        $teamB = $player_sanction->game->gameScheduling->teamB;
       
       
        if($player_sanction->player->team->id === $teamA->id){
            $totalYellowCardsTeamA= $player_sanction->game->gameStatistic->yellow_cards_a + intval($player_sanction->yellow_cards);
            $totalRedCardsTeamA= $player_sanction->game->gameStatistic->red_cards_a + intval($player_sanction->red_card);

            $player_sanction->game->gameStatistic->update([
                'yellow_cards_a' => $totalYellowCardsTeamA,
                'red_cards_a' => $totalRedCardsTeamA,
            ]);
        }else if($player_sanction->player->team->id === $teamB->id){
            $totalYellowCardsTeamB= $player_sanction->game->gameStatistic->yellow_cards_b + intval($player_sanction->yellow_cards);
            $totalRedCardsTeamB= $player_sanction->game->gameStatistic->red_cards_b + intval($player_sanction->red_card);

            $player_sanction->game->gameStatistic->update([
                'yellow_cards_b' => $totalYellowCardsTeamB,
                'red_cards_b' => $totalRedCardsTeamB,
            ]);
        }
     
        return redirect()->route('player_sanctions.index')->with('success', 'Sanción creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(PlayerSanction $playerSanction): Response
    {
        $player_sanctions = PlayerSanction::with('game.gameScheduling.teamA', 'game.gameScheduling.teamB', 'player.team.club','game.gameScheduling.gameRole.tournament');
        $player_sanctions = $player_sanctions->get();

        $playerSanction->load('game.gameScheduling.teamA', 'game.gameScheduling.teamB','game.gameScheduling.gameRole.tournament', 'player.team');

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
        
        $playerSanction->load('game.gameScheduling.teamA',  'game.gameScheduling.teamB','game.gameScheduling.gameRole.tournament', 'player.team');

        $games = Game::with('gameScheduling.teamA.players', 'gameScheduling.teamB.players', 'gameScheduling.gameRole.tournament','playerSanctions.player')->get();
        $players = Player::with('team.club')->get();
        //dd($playerSanction, $games);
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
        $oldGameId = $player_sanction->game_id;
        $oldPlayerId = $player_sanction->player_id;
        $oldYellowCards = $player_sanction->yellow_cards;
        $oldRedCard = $player_sanction->red_card;

        $player_sanction->update($validatedData); 

        $newYellowCards = intval($player_sanction->yellow_cards);
        $newRedCard = intval($player_sanction->red_card);
        //dd($oldredCard, $oldYellowCards, $newredCard, $newYellowCards);


        $newGameId = $player_sanction->game_id;
        $newPlayerId = $player_sanction->player_id;
       // dd($oldPlayerId, $newPlayerId);
       $teamA = $player_sanction->game->gameScheduling->teamA;
       $teamB = $player_sanction->game->gameScheduling->teamB;

        $oldGame= Game::find($oldGameId)->load('gameStatistic','gameScheduling.teamA','gameScheduling.teamB');
        //dd($oldGame);
        //Recuperar equipos antiguos
        $oldPlayer= Player::find($oldPlayerId)->load('team');
        
        $oldTeamA = $oldGame->gameScheduling?->teamA;
        $oldTeamB = $oldGame->gameScheduling?->teamB;

        //Verificar si no cambia ni jugador ni partido, o solo cambia el jugador
        if($newGameId === $oldGameId && $newPlayerId === $oldPlayerId || $newGameId === $oldGameId && $newPlayerId !== $oldPlayerId){

            if($oldYellowCards != $newYellowCards){
                if($player_sanction->player->team->id === $teamA->id){
                    $totalYellowCardsTeamA= $player_sanction->game->gameStatistic->yellow_cards_a + $newYellowCards - $oldYellowCards;        
                    $player_sanction->game->gameStatistic->update([
                        'yellow_cards_a' => $totalYellowCardsTeamA,
                    ]);
                }else if($player_sanction->player->team->id === $teamB->id){
                    $totalYellowCardsTeamB= $player_sanction->game->gameStatistic->yellow_cards_b + $newYellowCards - $oldYellowCards;                
                    $player_sanction->game->gameStatistic->update([
                        'yellow_cards_b' => $totalYellowCardsTeamB,
                    ]);
                }
            }

            if($oldRedCard != $newRedCard){
                if($player_sanction->player->team->id === $teamA->id){
                    $totalRedCardTeamA= $player_sanction->game->gameStatistic->red_cards_a + $newRedCard - $oldRedCard;        
                    $player_sanction->game->gameStatistic->update([
                        'red_cards_a' => $totalRedCardTeamA,
                    ]);
                }else if($player_sanction->player->team->id === $teamB->id){
                    $totalRedCardTeamB= $player_sanction->game->gameStatistic->red_cards_b + $newRedCard - $oldRedCard;                
                    $player_sanction->game->gameStatistic->update([
                        'red_cards_b' => $totalRedCardTeamB,
                    ]);
                }
            }
        }

        // Verificar si solo cambia el partido pero el jugador permanece igual, o si cambian tanto el jugador como el partido
        if ($newGameId !== $oldGameId && $newPlayerId === $oldPlayerId || $newGameId !== $oldGameId && $newPlayerId !== $oldPlayerId) {
           
            //Quitar tarjetas amarillas y rojas al equipo antiguo
            if($oldPlayer->team->id === $oldTeamA->id){
                $updateYellowCards= $oldGame->gameStatistic->yellow_cards_a - $oldYellowCards;
                $updateRedCards= $oldGame->gameStatistic->red_cards_a - $oldRedCard;
                $oldGame->gameStatistic->update([
                    'yellow_cards_a' => $updateYellowCards,
                    'red_cards_a' => $updateRedCards,
                ]);
            }else if($oldPlayer->team->id === $oldTeamB->id){
                $updateYellowCards= $oldGame->gameStatistic->yellow_cards_b - $oldYellowCards;
                $updateRedCards= $oldGame->gameStatistic->red_cards_b - $oldRedCard;
                $oldGame->gameStatistic->update([
                    'yellow_cards_b' => $updateYellowCards,
                    'red_cards_b' => $updateRedCards,
                ]);
            }
            //Adicionar tarjetas amarillas y rojas al equipo nuevo
            if($player_sanction->player->team->id === $teamA->id){
                $totalYellowCardsTeamA= $player_sanction->game->gameStatistic->yellow_cards_a + $newYellowCards;   
                $totalRedCardTeamA= $player_sanction->game->gameStatistic->red_cards_a + $newRedCard;        
 
                $player_sanction->game->gameStatistic->update([
                    'yellow_cards_a' => $totalYellowCardsTeamA,
                    'red_cards_a' => $totalRedCardTeamA,
                ]);
            }else if($player_sanction->player->team->id === $teamB->id){
                $totalYellowCardsTeamB= $player_sanction->game->gameStatistic->yellow_cards_b + $newYellowCards;    
                $totalRedCardTeamB= $player_sanction->game->gameStatistic->red_cards_b + $newRedCard;        
                                    
                $player_sanction->game->gameStatistic->update([
                    'yellow_cards_b' => $totalYellowCardsTeamB,
                    'red_cards_b' => $totalRedCardTeamB,

                ]);
            }                      
        }
        
/*         // Verificar si cambian tanto el jugador como el partido
        if ($newGameId !== $oldGameId && $newPlayerId !== $oldPlayerId) {
            // Restar las tarjetas amarillas y rojas del equipo del partido anterior

            //Quitar tarjetas amarillas y rojas al equipo antiguo
            $updateYellowCards= $oldGame->gameStatistic->yellow_cards_a - $oldYellowCards;
            $updateRedCards= $oldGame->gameStatistic->red_cards_a - $oldRedCard;
            $oldGame->gameStatistic->update([
                'yellow_cards_a' => $updateYellowCards,
                'red_cards_a' => $updateRedCards,
            ]);  
            // Sumar las nuevas tarjetas amarillas y rojas al equipo del nuevo partido
            if($player_sanction->player->team->id === $teamA->id){
                $totalYellowCardsTeamA= $player_sanction->game->gameStatistic->yellow_cards_a + $newYellowCards;   
                $totalRedCardTeamA= $player_sanction->game->gameStatistic->red_cards_a + $newRedCard;        
 
                $player_sanction->game->gameStatistic->update([
                    'yellow_cards_a' => $totalYellowCardsTeamA,
                    'red_cards_a' => $totalRedCardTeamA,
                ]);
            }else if($player_sanction->player->team->id === $teamB->id){
                $totalYellowCardsTeamB= $player_sanction->game->gameStatistic->yellow_cards_b + $newYellowCards;    
                $totalRedCardTeamB= $player_sanction->game->gameStatistic->red_cards_b + $newRedCard;        
                                    
                $player_sanction->game->gameStatistic->update([
                    'yellow_cards_b' => $totalYellowCardsTeamB,
                    'red_cards_b' => $totalRedCardTeamB,

                ]);
            }    
            
        }

        // Verificar si solo cambia el jugador pero el partido permanece igual
        if ($newGameId === $oldGameId && $newPlayerId !== $oldPlayerId) {
            // Restar las tarjetas amarillas y rojas del equipo del partido anterior
            // Sumar las nuevas tarjetas amarillas y rojas al equipo del partido actual
        

            //dd($oldTeamA, $oldTeamB);
            if($oldPlayerId != $newPlayerId){
                if($oldPlayer->team->id === $oldTeamA->id){
                    $updateYellowCards= $oldGame->gameStatistic->yellow_cards_a - $oldYellowCards;
                    $updateRedCards= $oldGame->gameStatistic->red_cards_a - $oldRedCard;
                    $oldGame->gameStatistic->update([
                        'yellow_cards_a' => $updateYellowCards,
                        'red_cards_a' => $updateRedCards,
                    ]);
                }else if($oldPlayer->team->id === $oldTeamB->id){
                    $updateYellowCards= $oldGame->gameStatistic->yellow_cards_b - $oldYellowCards;
                    $updateRedCards= $oldGame->gameStatistic->red_cards_b - $oldRedCard;
                    $oldGame->gameStatistic->update([
                        'yellow_cards_b' => $updateYellowCards,
                        'red_cards_b' => $updateRedCards,
                    ]);
                }
            }
          
            if($oldYellowCards != $newYellowCards){
                if($player_sanction->player->team->id === $teamA->id){
                    $totalYellowCardsTeamA= $player_sanction->game->gameStatistic->yellow_cards_a + $newYellowCards - $oldYellowCards;        
                    $player_sanction->game->gameStatistic->update([
                        'yellow_cards_a' => $totalYellowCardsTeamA,
                    ]);
                }else if($player_sanction->player->team->id === $teamB->id){
                    $totalYellowCardsTeamB= $player_sanction->game->gameStatistic->yellow_cards_b + $newYellowCards - $oldYellowCards;                
                    $player_sanction->game->gameStatistic->update([
                        'yellow_cards_b' => $totalYellowCardsTeamB,
                    ]);
                }
            }

            if($oldRedCard != $newRedCard){
                if($player_sanction->player->team->id === $teamA->id){
                    $totalRedCardTeamA= $player_sanction->game->gameStatistic->red_cards_a + $newRedCard - $oldRedCard;        
                    $player_sanction->game->gameStatistic->update([
                        'red_cards_a' => $totalRedCardTeamA,
                    ]);
                }else if($player_sanction->player->team->id === $teamB->id){
                    $totalRedCardTeamB= $player_sanction->game->gameStatistic->red_cards_b + $newRedCard - $oldRedCard;                
                    $player_sanction->game->gameStatistic->update([
                        'red_cards_b' => $totalRedCardTeamB,
                    ]);
                }
            }
        }
          
        if($player_sanction->player->team->id === $teamA->id){
            $totalYellowCardsTeamA= $player_sanction->game->gameStatistic->yellow_cards_a + intval($player_sanction->yellow_cards);
            $totalRedCardsTeamA= $player_sanction->game->gameStatistic->red_cards_a + intval($player_sanction->red_card);

            $player_sanction->game->gameStatistic->update([
                'yellow_cards_a' => $totalYellowCardsTeamA,
                'red_cards_a' => $totalRedCardsTeamA,
            ]);
        }else if($player_sanction->player->team->id === $teamB->id){
            $totalYellowCardsTeamB= $player_sanction->game->gameStatistic->yellow_cards_b + intval($player_sanction->yellow_cards);
            $totalRedCardsTeamB= $player_sanction->game->gameStatistic->red_cards_b + intval($player_sanction->red_card);

            $player_sanction->game->gameStatistic->update([
                'yellow_cards_b' => $totalYellowCardsTeamB,
                'red_cards_b' => $totalRedCardsTeamB,
            ]);
        } */
        return redirect()->route('player_sanctions.index')->with('success', 'Partido actualizada correctamente');        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlayerSanction $playerSanction): RedirectResponse
    {       

        //$this->authorize('delete', $game);
        $oldGameId = $playerSanction->game_id;
        $oldPlayerId = $playerSanction->player_id;
        $oldYellowCards = $playerSanction->yellow_cards;
        $oldRedCard = $playerSanction->red_card;
        //dd($oldGameId, $oldPlayerId, $oldYellowCards, $oldRedCard);
        $oldGame= Game::find($oldGameId)->load('gameStatistic','gameScheduling.teamA', 'gameScheduling.teamB');
        $oldTeamA = $oldGame->gameScheduling->teamA;
        $oldTeamB = $oldGame->gameScheduling->teamB;
        
        //Recuperar equipos antiguos
        $oldPlayer= Player::find($oldPlayerId)->load('team');
           

        //Quitar tarjetas amarillas y rojas al equipo antiguo
        if($oldPlayer->team->id === $oldTeamA->id){
            $updateYellowCards= $oldGame->gameStatistic->yellow_cards_a - $oldYellowCards;
            $updateRedCards= $oldGame->gameStatistic->red_cards_a - $oldRedCard;
            $oldGame->gameStatistic?->update([
                'yellow_cards_a' => $updateYellowCards,
                'red_cards_a' => $updateRedCards,
            ]);
        }else if($oldPlayer->team->id === $oldTeamB->id){
            $updateYellowCards= $oldGame->gameStatistic->yellow_cards_b - $oldYellowCards;
            $updateRedCards= $oldGame->gameStatistic->red_cards_b - $oldRedCard;
            $oldGame->gameStatistic?->update([
                'yellow_cards_b' => $updateYellowCards,
                'red_cards_b' => $updateRedCards,
            ]);
        }
        $playerSanction->delete();       

        return to_route('player_sanctions.index');
    }
}
