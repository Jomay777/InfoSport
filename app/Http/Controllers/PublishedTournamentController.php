<?php

namespace App\Http\Controllers;

use App\Http\Resources\TeamResource;
use App\Http\Resources\TournamentResource;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PublishedTournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $publishedTournaments = Tournament::published()->with('gameRoles.gameSchedulings.teamA.club','gameRoles.gameSchedulings.teamA.gameSchedulingsAsTeamA', 'gameRoles.gameSchedulings.teamB.club','gameRoles.gameSchedulings.teamB.gameSchedulingsAsTeamB', 'gameRoles.pitch')->get();
        $gameRoles = $publishedTournaments->pluck('gameRoles')->flatten()->unique();
        $gameSchedulings = $gameRoles->pluck('gameSchedulings')->flatten()->unique();

        $teamsA = $gameSchedulings->pluck('teamA')->flatten()->unique();
        $teamsB = $gameSchedulings->pluck('teamB')->flatten()->unique();
 /*        $teamsA = $teamsA->map(function ($team) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'description' => $team->description,
                'club_id' => $team->club_id,
                'category_id' => $team->category_id,
                'gameSchedulingsAsTeamA' => $team->gameSchedulingsAsTeamA->map(function ($gameScheduling) {
                    return [
                        'id' => $gameScheduling->id,
                        'team_id' => $gameScheduling->team_id,
                    ];
                }),
                'club' => [
                    'logo_path' => $team->club->logo_path,
                ],
            ];
        }); */
/*         $teamsA = $teamsA->map(function ($team) {
            return [
                'id' => $team['id'],
                'name' => $team['name'],
                'description' => $team['description'],
                'club_id' => $team['club_id'],
                'category_id' => $team['category_id'],
                'gameSchedulingsAsTeamA' => [
                    'id' => $team['gameSchedulingsAsTeamA']['id'],
                    'team_id' => $team['gameSchedulingsAsTeamA']['team_id'],
                ],
                'club' => [
                'logo_path' => $team['club']['logo_path'],
                ],
            ];
        }); */
        //$teamsA = $teamsA->load('gameSchedulingsAsTeamA.gameRole')->get();
        //getting teams_a filtered
/*         $teamsA = $teams->filter(function ($team, $index) {
            return $index % 2 === 0;
        })->map(function ($team) {
            return [
                'id' => $team['id'],
                'name' => $team['name'],
                'description' => $team['description'],
                'club_id' => $team['club_id'],
                'category_id' => $team['category_id'],
                'pivot' => [
                    'game_scheduling_id' => $team['pivot']['game_scheduling_id'],
                    'team_id' => $team['pivot']['team_id'],
                ],
                'club' => [
                'logo_path' => $team['club']['logo_path'],
                ],
            ];
        });
        
        $teamsB = $teams->filter(function ($team, $index) {
            return $index % 2 !== 0;
        })->map(function ($team) {
            return [
                'id' => $team['id'],
                'name' => $team['name'],
                'description' => $team['description'],
                'club_id' => $team['club_id'],
                'category_id' => $team['category_id'],
                'pivot' => [
                    'game_scheduling_id' => $team['pivot']['game_scheduling_id'],
                    'team_id' => $team['pivot']['team_id'],
                ],
                'club' => [
                    'logo_path' => $team['club']['logo_path'],
                ],
            ];
        }); */
       //dd($publishedTournaments, $gameRoles, $gameSchedulings, $teamsA, $teamsB);
        return Inertia::render('PublishedTournaments/PublishedTournamentIndex', [
            'published_tournaments' => TournamentResource::collection($publishedTournaments),
            'teams_a' => TeamResource::collection($teamsA),
            'teams_b' => TeamResource::collection($teamsB),
            'game_roles' => $gameRoles,
            'game_schedulings' => $gameSchedulings,
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
