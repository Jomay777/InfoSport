<?php

namespace App\Http\Controllers;

use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Http\Request;
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
