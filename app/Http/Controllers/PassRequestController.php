<?php

namespace App\Http\Controllers;

use App\Http\Requests\PassRequestRequest;
use App\Http\Requests\UpdatePassRequestRequest;
use App\Http\Resources\PassRequestResource;
use App\Http\Resources\PlayerResource;
use App\Models\PassRequest;
use App\Models\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PassRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $user->load('clubs.teams.players');

        $passRequests = PassRequest::with('player')
            ->latest()
            ->take(20);

        // Filtrar según las relaciones del usuario con clubes, equipos y jugadores
        if (!$user->clubs->isEmpty() && !$user->clubs->pluck('teams')->isEmpty()) {
            $playerIds = $user->clubs->pluck('teams.*.players.*.id')->flatten()->toArray();
            $passRequests->whereIn('player_id', $playerIds);
        }

        // Aplicar la búsqueda si se proporciona un término de búsqueda
        if ($request->search) {
            $fullName = $request->search;
            $names = explode(' ', $fullName);
            $firstName = isset($names[0]) ? $names[0] : '';
            $passRequests->where(function ($query) use ($request, $fullName, $firstName) {
                $query->where('pass_requests.id', 'like', '%' . $request->search . '%')
                    ->orWhereHas('player', function ($subQuery) use ($firstName, $fullName, $request) {
                        $subQuery->where('first_name', 'like', '%' . $firstName . '%')                            
                            ->orWhere('first_name', 'like', '%' . $request->search . '%')
                            ->orWhere('second_name', 'like', '%' . $request->search . '%')
                            ->orWhere('last_name', 'like', '%' . $request->search . '%')
                            ->orWhere('mother_last_name', 'like', '%' . $request->search . '%')
                            ->orWhereRaw("CONCAT(first_name, ' ', second_name, ' ', last_name, ' ', mother_last_name) LIKE ?", ['%' . $fullName . '%']);
                    });
            });
        }
        
        

        // Obtener los resultados finales
        $passRequests = $passRequests->get();

        return Inertia::render('Admin/PassRequests/PassRequestIndex', [
            'pass_requests' => PassRequestResource::collection($passRequests),
            'search' => $request->search, 
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', PassRequest::class);

        $user = Auth::user();
        $user->load('clubs.teams.players');

        // Obtén todos los jugadores si el usuario no tiene asignado un club o equipos
        // De lo contrario, obtén solo los jugadores relacionados con los clubes y equipos del usuario
        $players = $user->clubs->isEmpty() || $user->clubs->pluck('teams')->isEmpty()
            ? Player::with('team')->get()
            : $user->clubs->pluck('teams.*.players')->flatten();
        return Inertia::render('Admin/PassRequests/Create', [
            'player' =>  PlayerResource::collection($players),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PassRequestRequest $request)
    {
        $this->authorize('create', PassRequest::class);
        $validatedData = $request->validated();
        if ($request->has('player')) {
            $validatedData['player_id']= $request->input('player.id');
        } 
        if ($request->hasFile('request_photo_path')) {
            // Store the file in the 'public/pass_request' directory
            $passRequestPath = $request->file('request_photo_path')->store('public/pass_request');
            $validatedData['request_photo_path'] = str_replace('public/', '/storage/', $passRequestPath);
        } 
        
        PassRequest::create($validatedData);
        return redirect()->route('pass_requests.index')->with('success', 'Solicitud de pase de jugador creado con exito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PassRequest $pass_request): Response
    {

        $player = $pass_request->player;
//        dd($photopass_request);

        return Inertia::render('Admin/PassRequests/Show', [
            'pass_request' => new PassRequestResource($pass_request),
            'player' => $player,
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PassRequest $pass_request): Response
    {    
        $this->authorize('update', $pass_request);

        $pass_request->load('player');
        return Inertia::render('Admin/PassRequests/Edit', [
            'pass_request' => new PassRequestResource($pass_request),
            'player' => PlayerResource::collection(Player::all())
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePassRequestRequest $request, string $id)
    {
        $pass_request = PassRequest::find($id);
        $this->authorize('update', $pass_request);

         //dd($request->all());
        $validatedData = $request->validated();
        if ($request->has('player')) {
            $validatedData['player_id']= $request->input('player.id');
        } 
        
        if ($request->hasFile('request_photo_path')) {
            if ($pass_request->request_photo_path) {
                $filename = basename($pass_request->request_photo_path);        
                Storage::delete('public/pass_request/' . $filename);
            }
            $passRequestPath = $request->file('request_photo_path')->store('public/pass_request');

            $validatedData['request_photo_path'] = str_replace('public/', '/storage/', $passRequestPath);
        }
        else {
            $filePath = $pass_request->request_photo_path;
            $validatedData['request_photo_path'] = $filePath;
        }
        $pass_request->update($validatedData);
         return redirect()->route('pass_requests.index')->with('success', 'Solicitud de pase de jugador actualizado con exito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PassRequest $pass_request): RedirectResponse
    {
        $this->authorize('delete', $pass_request);

        //destroy file of public
        if ($pass_request->request_photo_path) {
            $filename = basename($pass_request->request_photo_path);
            Storage::delete('public/pass_request/' . $filename);       
        }
        $pass_request->delete();        
        return to_route('pass_requests.index');
    }
}
