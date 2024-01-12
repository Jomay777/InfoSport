<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTournamentRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TournamentResource;
use App\Models\Category;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $tournaments = Tournament::with('category');

        if ($request->search) {
            $tournaments->where('tournaments.name', 'like', '%' . $request->search . '%');
        }

        $tournaments = $tournaments->get();

        return Inertia::render('Admin/Tournaments/TournamentIndex', [
            'tournaments' => TournamentResource::collection($tournaments),
            'search' => $request->search, // Pasa el valor de búsqueda a la vista
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Tournaments/Create', [
            'category' => CategoryResource::collection(Category::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTournamentRequest $request)
    {
        $validatedData = $request->validated();
        $tournament =  Tournament::create($validatedData);

        if ($request->has('category')) {
            $categoryId = $request->input('category.id');

            $tournament->update(['category_id' => $categoryId]);
        }        
        return redirect()->route('tournaments.index')->with('success', 'Torneo creado correctamente');
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
    public function edit(Tournament $tournament): Response
    {    
        $tournament->load('category');
        return Inertia::render('Admin/Tournaments/Edit', [
            'tournament' => new TournamentResource($tournament),
            'category' => CategoryResource::collection(Category::all())
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(CreateTournamentRequest $request, string $id):RedirectResponse
    {        
        $tournament = Tournament::find($id);               
        $validatedData = $request->validated();
        $tournament->update($validatedData);
        if ($request->has('category')) {
            $categoryId = $request->input('category.id');

            $tournament->update(['category_id' => $categoryId]);
        }  
        
        return to_route('tournaments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tournament $tournament): RedirectResponse
    {
        $tournament->delete();
        return to_route('tournaments.index');
    }
}
