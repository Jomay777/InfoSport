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
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = User::with('clubs', 'roles');
        $users = $users->get();
        //dd($team_sanctions);
        return Inertia::render('Admin/ReportIndex', [
            'users' => UserResource::collection($users),
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function user()
    {
        dd('hola');
    }
    public function club(): Response
    {
        $clubs = Club::with('users');
        $clubs = $clubs->get();
        return Inertia::render('Admin/AllReports/ClubsReport', [
            'clubs' => ClubResource::collection($clubs),            
        ]);
    }
    public function team(): Response
    {
        $teams = Team::with('category', 'club');
        $teams = $teams->get();
        return Inertia::render('Admin/AllReports/TeamsReport', [
            'teams' => TeamResource::collection($teams),            
        ]);
    }
    public function player(): Response
    {
        $players = Player::with('team', 'photoPlayer');
        $players = $players->get();
        return Inertia::render('Admin/AllReports/PlayersReport', [
            'players' => PlayerResource::collection($players),
        ]);
    }
    public function passRequest(): Response
    {
        $passRequests = PassRequest::with('player');
        $passRequests = $passRequests->get();
        return Inertia::render('Admin/AllReports/PassRequestsReport', [
            'pass_requests' => PassRequestResource::collection($passRequests),
        ]);
    }
    public function category(): Response
    {
        $categories = Category::query();
        $categories = $categories->get();
        return Inertia::render('Admin/AllReports/CategoriesReport', [
            'categories' => CategoryResource::collection($categories),
        ]);
    }
    public function tournament(): Response
    {
        $tournaments = Tournament::with('category');
        $tournaments = $tournaments->get();
        return Inertia::render('Admin/AllReports/TournamentsReport', [
            'tournaments' => TournamentResource::collection($tournaments),
        ]);
    }
    public function gameRole(): Response
    {
        $game_roles = GameRole::with('tournament', 'pitch');
        $game_roles = $game_roles->get();
        return Inertia::render('Admin/AllReports/GameRolesReport', [
            'game_roles' => GameRoleResource::collection($game_roles),
        ]);
    }
    public function gameScheduling(): Response
    {
        $game_schedulings = GameScheduling::with('teamA', 'teamB', 'gameRole');
        $game_schedulings = $game_schedulings->get();
        return Inertia::render('Admin/AllReports/GameSchedulingsReport', [
            'game_schedulings' => GameSchedulingResource::collection($game_schedulings),
        ]);
    }
    public function game(): Response
    {
        $games = Game::with('gameScheduling.teamA', 'gameScheduling.teamB', 'gameStatistic','gameScheduling.gameRole');
        $games = $games->get();
        return Inertia::render('Admin/AllReports/GamesReport', [
            'games' => GameResource::collection($games),
        ]);
    }
    public function playerSanction(): Response
    {
        $player_sanctions = PlayerSanction::with('game.gameScheduling.teamA', 'game.gameScheduling.teamB', 'player.team.club','game.gameScheduling.gameRole.tournament');
        $player_sanctions = $player_sanctions->get();
        return Inertia::render('Admin/AllReports/PlayerSanctionsReport', [
            'player_sanctions' => PlayerSanctionResource::collection($player_sanctions),
        ]);
    }
    public function teamSanction(): Response
    {
        $team_sanctions = TeamSanction::with('game.gameScheduling.teamA', 'game.gameScheduling.teamB', 'team.club','game.gameScheduling.gameRole.tournament');
        $team_sanctions = $team_sanctions->get();
        return Inertia::render('Admin/AllReports/TeamSanctionsReport', [
            'team_sanctions' => TeamSanctionResource::collection($team_sanctions),
        ]);
    }
  
}
