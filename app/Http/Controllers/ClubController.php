<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClubRequest;
use App\Http\Resources\ClubResource;
use App\Models\Club;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClubController extends Controller
{
    public function index(): Response
    {
        $clubs = Club::all();
        return Inertia::render('Admin/Clubs/ClubIndex',[
            'clubs' => ClubResource::collection($clubs)
        ]);
    }
    public function create(): Response
    {
        return Inertia::render('Admin/Clubs/Create');
    }
    public function store(CreateClubRequest $request): RedirectResponse
    {
        Club::create($request->validated());
        return to_route('clubs.index');
    }
    public function edit(Club $club): Response
    {
        return Inertia::render('Admin/Clubs/Edit', [
            'club' => new ClubResource($club)
        ]);
    }
    public function delete(){
        
    }
}
