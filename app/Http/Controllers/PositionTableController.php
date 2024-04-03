<?php

namespace App\Http\Controllers;

use App\Http\Resources\PositionTableResource;
use App\Http\Resources\TournamentResource;
use App\Models\PositionTable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PositionTableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $positionTables = PositionTable::with('team.club', 'tournament', 'team.gameSchedulingsAsTeamA.game.gameStatistic', 'team.gameSchedulingsAsTeamB.game.gameStatistic')->get();
        $teams = $positionTables->pluck('team')->flatten()->unique();
        $tournaments = $positionTables->pluck('tournament')->flatten()->unique();
        $gameSchedulings = $teams->pluck('gameSchedulings')->flatten()->unique();
        $games = $gameSchedulings->pluck('game')->flatten()->unique();
     /*    $tournamentsArray = $tournaments->map(function ($tournament) {
            return $tournament->toArray();
        })->toArray(); */

        //$club = $team->pluck('club')->flatten()->unique();

        // Agrupar los registros por tournament_id
        $positionTablesByTournament = $positionTables->groupBy('tournament_id');

        // Iterar sobre cada grupo y convertirlo en una colección de recursos
        $positionTablesByTournament = $positionTablesByTournament->map(function ($group) {
            return PositionTableResource::collection($group);
        });
        //dd($positionTables, $positionTablesByTournament, $tournaments, $games , $teams);
        return Inertia::render('PositionTables/PositionTableIndex', [
            'position_tables' => PositionTableResource::collection($positionTables),
            'position_tables_by_tournament' => $positionTablesByTournament,
            'tournaments' =>  TournamentResource::collection($tournaments),

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
    public function show(PositionTable $positionTable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PositionTable $positionTable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PositionTable $positionTable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PositionTable $positionTable)
    {
        //
    }
}
