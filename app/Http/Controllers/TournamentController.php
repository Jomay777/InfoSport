<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTournamentRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TournamentResource;
use App\Models\Category;
use App\Models\LogTransaction;
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
        $tournaments = Tournament::with('category')
        ->latest()  
        ->take(20); 

        if ($request->search) {
            $tournaments->where('tournaments.name', 'like', '%' . $request->search . '%')
                ->orWhere('tournaments.id', 'like', $request->search)
                ->orWhere('tournaments.state', 'like', $request->search . '%')
                ->orWhereHas('category', function ($query) use ($request) {
                    $query->where('name', 'like', $request->search);
                });
        }

        $tournaments = $tournaments->get();
        //dd($tournaments);
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
        $this->authorize('create', Tournament::class);
        return Inertia::render('Admin/Tournaments/Create', [
            'category' => CategoryResource::collection(Category::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTournamentRequest $request)
    {
        $this->authorize('create', Tournament::class);

        $validatedData = $request->validated();
        if ($request->has('state')) {
            $validatedData['state']= $request->input('state.name');
        } 
        $tournament =  Tournament::create($validatedData);

        if ($request->has('category')) {
            $categoryId = $request->input('category.id');

            $tournament->update(['category_id' => $categoryId]);
        }  
        //Creating log transactions
        $details = 'Nombre: ' . $tournament->name . ', Estado: ' . $tournament->state. ', Id de Categoría: ' . $tournament->category_id;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Crear', // HTTP method used for the request
            'resource' => 'Torneo', // Name of the resource being accessed
            'resource_id' => $tournament?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);      
        return redirect()->route('tournaments.show', $tournament->id)->with('success', 'Torneo creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tournament $tournament): Response
    {

        $category = $tournament->category;

        return Inertia::render('Admin/Tournaments/Show', [
            'tournament' => new TournamentResource($tournament),
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tournament $tournament): Response
    {    
        $this->authorize('update', $tournament);

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
        $this->authorize('update', $tournament);

        $validatedData = $request->validated();
        if ($request->has('state')) {
            $validatedData['state']= $request->input('state.name');
        } 

        //Old Data
        $oldName=$tournament->name;
        $oldState=$tournament->state;
        $oldCategoryId = $tournament->category_id;

        $tournament->update($validatedData);
        if ($request->has('category')) {
            $categoryId = $request->input('category.id');

            $tournament->update(['category_id' => $categoryId]);
        } 
        
        //Creating log transactions

        $details = '[Nombre: ' . $oldName . '. a: ' . $tournament->name . '], [Estado: ' . $oldState . '. a: ' . $tournament->state . '], [Id de Categoría: ' . $oldCategoryId . '. a: ' . $tournament->category_id . ']';
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Actualizar', // HTTP method used for the request
            'resource' => 'Torneo', // Name of the resource being accessed
            'resource_id' => $tournament?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);     
        
        return to_route('tournaments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tournament $tournament): RedirectResponse
    {
        $this->authorize('delete', $tournament);

        //Creating log transactions
        $details = 'Nombre: ' . $tournament->name . ', Estado: ' . $tournament->state. ', Id de Categoría: ' . $tournament->category_id;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Eliminar', // HTTP method used for the request
            'resource' => 'Torneo', // Name of the resource being accessed
            'resource_id' => $tournament?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);      
        $tournament->delete();
        return to_route('tournaments.index');
    }
}
