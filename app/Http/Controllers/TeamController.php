<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ClubResource;
use App\Http\Resources\TeamResource;
use App\Models\Category;
use App\Models\Club;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $teams = Team::with('category', 'club')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  

        if ($request->search) {
            $teams->where(function ($query) use ($request) {
                $query->where('teams.name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('club', function ($subQuery) use ($request) {
                        $subQuery->where('clubs.name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        
        $teams = $teams->get();

        return Inertia::render('Admin/Teams/TeamIndex', [
            'teams' => TeamResource::collection($teams),
            'search' => $request->search, 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Teams/Create', [
            'category' => CategoryResource::collection(Category::all()),
            'club' => ClubResource::collection(Club::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TeamRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->has('club')) {
            $clubId = $request->input('club.id');
            $validatedData['club_id']= $clubId;
        } 

        if ($request->has('category')) {
            $categoryId = $request->input('category.id');
            $validatedData['category_id']= $categoryId;
        }  

        $team = Team::create($validatedData);                     
        return redirect()->route('teams.show', $team->id)->with('success', 'Torneo creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Team $team): Response
    {
        
        $club = $team->club;
        $category = $team->category;

        return Inertia::render('Admin/Teams/Show', [
            'team' => new TeamResource($team),
            'club' => $club,
            'category' => $category,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Team $team): Response
    {    
        $team->load('club','category');
        return Inertia::render('Admin/Teams/Edit', [
            'team' => new TeamResource($team),
            'club' => ClubResource::collection(Club::all()),
            'category' => CategoryResource::collection(Category::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TeamRequest $request, string $id):RedirectResponse
    {        
        $team = Team::find($id);               
        $validatedData = $request->validated();
        $team->update($validatedData);
        if ($request->has('club')) {
            $clubId = $request->input('club.id');

            $team->update(['club_id' => $clubId]);
        }  
        if ($request->has('category')) {
            $categoryId = $request->input('category.id');

            $team->update(['category_id' => $categoryId]);
        }  
        
        return to_route('teams.show', $team->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Team $team): RedirectResponse
    {
        $team->delete();
        return to_route('teams.index');
    }
}
