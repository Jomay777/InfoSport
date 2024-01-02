<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClubRequest;
use App\Http\Resources\ClubResource;
use App\Http\Resources\UserResource;
use App\Models\Club;
use App\Models\User;
use COM;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ClubController extends Controller
{
    public function index(): Response
    {
        $clubs = Club::with('users')->get();

        return Inertia::render('Admin/Clubs/ClubIndex', [
            'clubs' => ClubResource::collection($clubs),
        ]);        
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Clubs/Create', [
            'users' => UserResource::collection(User::all())
        ]);
    }

    public function store(CreateClubRequest $request)
    {
        $validatedData = $request->validated();
        
        $club = Club::create($validatedData);

        if (!$club) {
            // Handle club creation errors
            return back()->withErrors(['club' => 'Failed to create the club.']);
        }

        $userIds = collect($request->input('users.*.name'))->map(function ($name) {
            return User::where('name', $name)->first()->id;    
            })->toArray();

        // Attach only valid user IDs
        foreach ($userIds as $userId) {
            // Verificar si la relación ya existe antes de adjuntar
            if (!$club->users->contains($userId)) {                
                $club->users()->attach($userId);
            }
        }        
        return redirect()->route('clubs.index');
    }

    public function show(Club $club): Response
    {
        // Recupera los datos del usuario relacionado con el club
        $users = $club->users;

        // Pasa los datos del club y del usuario al template
        return Inertia::render('Admin/Clubs/Show', [
            'club' => new ClubResource($club),
            'users' => $users,
        ]);
    }

    public function edit(Club $club): Response
    {        
        $club->load('users');
        return Inertia::render('Admin/Clubs/Edit', [
            'club' => new ClubResource($club),
            'users' => UserResource::collection(User::all())
        ]);
    }
    
    
    public function update(CreateClubRequest $request, string $id):RedirectResponse
    {
        $club = Club::find($id);

        $club->update([
            'name' => $request->name,
            'coach' => $request->coach,
            'logo_path' => $request->logo_path,
        ]);

        $newUserIds = collect($request->input('users.*.name'))->map(function ($name) {
            return User::where('name', $name)->first()->id;
        })->toArray();

        // Obtén los IDs de los usuarios actualmente relacionados con el club
        $existingUserIds = $club->users->pluck('id')->toArray();

        // IDs de usuarios que deben eliminarse
        $usersToRemove = array_diff($existingUserIds, $newUserIds);

        // Elimina las relaciones que deben eliminarse
        $club->users()->detach($usersToRemove);

        // Adjunta los nuevos usuarios (evitando duplicados)
        foreach ($newUserIds as $userId) {
            if (!$club->users->contains($userId)) {
                $club->users()->attach($userId);
            }
        }

        return to_route('clubs.show', $club->id);
    }
    public function destroy(Club $club, User $user): RedirectResponse
    {
        $existingUserIds = $club->users->pluck('id')->toArray();
        $club->users()->detach($existingUserIds);

        $club->delete();
        return to_route('clubs.index');
    }    
}
