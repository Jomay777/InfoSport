<?php

namespace App\Http\Controllers;

use App\Http\Resources\GameSchedulingResource;
use App\Models\GameRole;
use App\Models\GameScheduling;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $latestUpdatedGameRole = GameRole::latest('updated_at')->first();

        $latestUpdatedGameRole->load('gameSchedulings.teamA.club', 'tournament', 'pitch');

        // Renderizar la vista de Inertia con los datos
        return Inertia::render('Dashboard', [
            'game_role' => $latestUpdatedGameRole,
            'game_scheduling' => GameSchedulingResource::collection(GameScheduling::all()),
        ]);
    }
}
