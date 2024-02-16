<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClubRequest;
use App\Http\Requests\UpdateClubRequest;
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
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

use function Laravel\Prompts\search;

class ClubController extends Controller
{
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $user->load('clubs');

        //dd($user);
        if ($user->clubs->isNotEmpty()) {
            $clubIds = $user->clubs->pluck('id')->toArray();
            $clubs = Club::with('users')
                ->whereIn('id', $clubIds) // Filtrar por los IDs de los clubes del usuario
                ->latest()
                ->take(20);
        } else {
            $clubs = Club::with('users')
                ->latest()
                ->take(20);
        }          

        if ($request->search) {
            $clubs->where(function ($query) use ($request) {
                $query->where('clubs.name', 'like', '%' . $request->search . '%')
                      ->orWhere('clubs.id', 'like', '%' . $request->search . '%')
                      ->orWhere('clubs.coach', 'like', '%' . $request->search . '%')
                      ->orWhereHas('users', function ($query) use ($request) {
                          $query->where('name', 'like', '%' . $request->search . '%');
                      });
            });
        }

        $clubs = $clubs->get();

        return Inertia::render('Admin/Clubs/ClubIndex', [
            'clubs' => ClubResource::collection($clubs),
            'search' => $request->search, // Pasa el valor de búsqueda a la vista
        ]);
    }
    
    public function create(): Response
    {
        $this->authorize('create', Club::class);
        return Inertia::render('Admin/Clubs/Create', [
            'users' => UserResource::collection(User::all())
        ]);
    }

    public function store(CreateClubRequest $request)
    {
        $this->authorize('create', Club::class);

        $validatedData = $request->validated();
        

         // Check if a file was uploaded for 'logo_path'
        if ($request->hasFile('logo_path')) {
            // Store the file in the 'public/logos' directory
            $logoPath = $request->file('logo_path')->store('public/logos');


            $validatedData['logo_path'] = str_replace('public/', '/storage/', $logoPath);
        }

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
        return redirect()->route('clubs.show', $club->id)->with('success', 'Club creado con exito.');
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
        $this->authorize('update', $club);
        $club->load('users');
        return Inertia::render('Admin/Clubs/Edit', [
            'club' => new ClubResource($club),
            'users' => UserResource::collection(User::all())
        ]);
    }
    
    
    public function update(UpdateClubRequest $request, string $id):RedirectResponse
    {

        $club = Club::find($id);   
        $this->authorize('update', $club);
    
        
        $validatedData = $request->validated();

        // Check if a file was uploaded for 'logo_path'
       if ($request->hasFile('logo_path')) {
        if ($club->logo_path) {
            // Extraer el nombre del archivo de la URL almacenada en la base de datos
            $filename = basename($club->logo_path);
    
            // Eliminar el archivo del directorio "public/logos"
            Storage::delete('public/logos/' . $filename);
        }
           // Store the file in the 'public/logos' directory
           $logoPath = $request->file('logo_path')->store('public/logos');
          
           $validatedData['logo_path'] = str_replace('public/', '/storage/', $logoPath);
        }else {
            $filePath = $club->logo_path;
            $validatedData['logo_path'] = $filePath;
        }
       $club->update($validatedData);
        
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
        $this->authorize('delete', $club);
        if ($club->logo_path) {
            // Extraer el nombre del archivo de la URL almacenada en la base de datos
            $filename = basename($club->logo_path);
    
            // Eliminar el archivo del directorio "public/logos"
            Storage::delete('public/logos/' . $filename);
        }
        $existingUserIds = $club->users->pluck('id')->toArray();
        $club->users()->detach($existingUserIds);

        $club->delete();
        return to_route('clubs.index');
    }    
}
