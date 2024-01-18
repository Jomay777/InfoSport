<?php

namespace App\Http\Controllers;

use App\Http\Resources\PhotoPlayerResource;
use App\Models\PhotoPlayer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PhotoPlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $photo_players = PhotoPlayer::with('player')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  

        if ($request->search) {
            $photo_players->where(function($query) use ($request) {
                $query->where('players.first_name', 'like', '%' . $request->search . '%')
                      ->orWhere('players.second_name', 'like', '%' . $request->search . '%')
                      ->orWhere('players.last_name', 'like', '%' . $request->search . '%')
                      ->orWhere('players.mother_last_name', 'like', '%' . $request->search . '%');
            });
        }
        $photo_players = $photo_players->get();

        //dd($photo_players->all());
        return Inertia::render('Admin/PhotoPlayers/PhotoPlayerIndex', [
            'photo_players' => PhotoPlayerResource::collection($photo_players),
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
