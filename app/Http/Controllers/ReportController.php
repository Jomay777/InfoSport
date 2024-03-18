<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ClubResource;
use App\Http\Resources\GameResource;
use App\Http\Resources\GameRoleResource;
use App\Http\Resources\GameSchedulingResource;
use App\Http\Resources\PassRequestResource;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\PlayerSanctionResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\TeamSanctionResource;
use App\Http\Resources\TournamentResource;
use App\Http\Resources\UserResource;
use App\Models\Category;
use App\Models\Club;
use App\Models\Game;
use App\Models\GameRole;
use App\Models\GameScheduling;
use App\Models\PassRequest;
use App\Models\Player;
use App\Models\PlayerSanction;
use App\Models\Team;
use App\Models\TeamSanction;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clubs = Club::with('users');
        $clubs = $clubs->get();
        $users = User::with('clubs', 'roles');
        $users = $users->get();
        $teams = Team::with('category', 'club');
        $teams = $teams->get();
        $players = Player::with('team', 'photoPlayer');
        $players = $players->get();
        $passRequests = PassRequest::with('player');
        $passRequests = $passRequests->get();
        $categories = Category::query();
        $categories = $categories->get();
        $tournaments = Tournament::with('category');
        $tournaments = $tournaments->get();
        $game_roles = GameRole::with('tournament', 'pitch');
        $game_roles = $game_roles->get();
        $game_schedulings = GameScheduling::with('teamA', 'teamB', 'gameRole');
        $game_schedulings = $game_schedulings->get();

        $games = Game::with('gameScheduling.teamA', 'gameScheduling.teamB', 'gameStatistic','gameScheduling.gameRole');
        $games = $games->get();
        //dd($users);
        $player_sanctions = PlayerSanction::with('game.gameScheduling.teamA', 'game.gameScheduling.teamB', 'player.team.club','game.gameScheduling.gameRole.tournament');
        $player_sanctions = $player_sanctions->get();
        $team_sanctions = TeamSanction::with('game.gameScheduling.teamA', 'game.gameScheduling.teamB', 'team.club','game.gameScheduling.gameRole.tournament');
        $team_sanctions = $team_sanctions->get();

        //dd($team_sanctions);

        return Inertia::render('Admin/ReportIndex', [
            'clubs' => ClubResource::collection($clubs),
            'users' => UserResource::collection($users),
            'teams' => TeamResource::collection($teams),
            'players' => PlayerResource::collection($players),
            'pass_requests' => PassRequestResource::collection($passRequests),
            'categories' => CategoryResource::collection($categories),
            'tournaments' => TournamentResource::collection($tournaments),
            'game_roles' => GameRoleResource::collection($game_roles),
            'game_schedulings' => GameSchedulingResource::collection($game_schedulings),
            'games' => GameResource::collection($games),
            'player_sanctions' => PlayerSanctionResource::collection($player_sanctions),
            'team_sanctions' => TeamSanctionResource::collection($team_sanctions),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
