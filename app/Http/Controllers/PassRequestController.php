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
        $pass_requests = PassRequest::with('player')
        ->latest()  // Ordena por la columna 'created_at' de forma descendente (más reciente primero)
        ->take(20);  
       
        if ($request->search) {
            $pass_requests->where(function ($query) use ($request) {
                $query->where('pass_requests.id', 'like', '%' . $request->search . '%')
                    ->orWhereHas('player', function ($subQuery) use ($request) {
                        $subQuery->where('first_name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('player', function ($subQuery) use ($request) {
                        $subQuery->where('second_name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('player', function ($subQuery) use ($request) {
                        $subQuery->where('last_name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('player', function ($subQuery) use ($request) {
                        $subQuery->where('mother_last_name', 'like', '%' . $request->search . '%');
                    });
            });
        }
        $pass_requests = $pass_requests->get();
        return Inertia::render('Admin/PassRequests/PassRequestIndex', [
            'pass_requests' => PassRequestResource::collection($pass_requests),
            'search' => $request->search, 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $players = Player::all();
        //dd($pass_requests);
        return Inertia::render('Admin/PassRequests/Create', [
            'player' =>  PlayerResource::collection($players),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PassRequestRequest $request)
    {
        //dd($request->all());
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PassRequest $pass_request): Response
    {    
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
        //destroy file of public
        if ($pass_request->request_photo_path) {
            $filename = basename($pass_request->request_photo_path);
            Storage::delete('public/pass_request/' . $filename);       
        }
        $pass_request->delete();        
        return to_route('pass_requests.index');
    }
}
