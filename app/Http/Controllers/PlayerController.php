<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PhotoPlayerResource;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\TeamResource;
use App\Models\LogTransaction;
use App\Models\PhotoPlayer;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
//use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Hash;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $user = Auth::user();
        $user->load('clubs.teams.players');

        $playersQuery = Player::with('team.category', 'photoPlayer');

        if ($user->clubs->pluck('teams')->flatten()->pluck('players')->flatten()->isNotEmpty()) {
            $playerIds = $user->clubs->pluck('teams.*.players.*.id')->flatten()->toArray();
            $playersQuery->whereIn('id', $playerIds);
        } else {
            $playersQuery->latest()->take(20);
        }
        if ($request->search) {
            $playersQuery->where(function ($query) use ($request) {
                $fullName = $request->search; // set fullname of search
                
                $names = explode(' ', $fullName);
                $firstName = isset($names[0]) ? $names[0] : '';
                /* $secondName = isset($names[1]) ? $names[1] : '';
                $lastName = isset($names[2]) ? $names[2] : '';
                $motherLastName = isset($names[3]) ? $names[3] : ''; */
        
                $query->where(function ($query) use ($firstName, $request) {
                    $query->where('players.first_name', 'like', '%' . $firstName . '%')                     
                        ->orWhere('players.first_name', 'like', '%' . $request->search . '%')
                        ->orWhere('players.second_name', 'like', '%' . $request->search . '%')
                        ->orWhere('players.last_name', 'like', '%' . $request->search . '%')
                        ->orWhere('players.id', 'like', '%' . $request->search . '%')
                        ->orWhere('players.c_i', 'like', '%' . $request->search . '%')
                        //->orWhere('players.state', 'like', '%' . $request->search . '%')

                        ->orWhereHas('team', function ($subQuery) use ($request) {
                            $subQuery->where('name', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('team.category', function ($subQuery) use ($request) {
                            $subQuery->where('name', 'like', '%' . $request->search . '%');
                        });
                         
                })
                // search full name
                ->orWhereRaw("CONCAT(players.first_name, ' ', players.second_name, ' ', players.last_name, ' ', players.mother_last_name) LIKE ?", ['%' . $fullName . '%']);
            });
        }
     
        $players = $playersQuery->latest()->take(20)->get();

        return Inertia::render('Admin/Players/PlayerIndex', [
            'players' => PlayerResource::collection($players),
            'search' => $request->search, 
        ]);
    }

    public function pdf(Player $player)
    {
       
        $player->load('team.club','photoPlayer');
        //dd($player);
        $pdf = Pdf::loadView('Players.player_pdf', compact('player'));
        return $pdf->stream();
        
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Player::class);

        $user = Auth::user();
        $user->load('clubs.teams.category');
    
        // Obtén todos los equipos si el usuario no tiene asignado un club
        // De lo contrario, obtén solo los equipos relacionados con el club del usuario
        $teams = $user->clubs->isEmpty()
            ? Team::with('club', 'category')->get()
            : $user->clubs->pluck('teams')->flatten();
            //dd($teams);
        return Inertia::render('Admin/Players/Create', [
            'team' => TeamResource::collection($teams),
            'photoPlayer' => PhotoPlayerResource::collection(PhotoPlayer::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PlayerRequest $request)
    {
        //dd($request->all());
        $this->authorize('create', Player::class);
        $validatedData = $request->validated();
        //$validatedData['c_i'] = encrypt($request->c_i);

        //dd($validatedData);
        if ($request->has('team')) {
            $teamId = $request->input('team.id');
            $validatedData['team_id']= $teamId;
        } 
        if ($request->has('state')) {
            $validatedData['state']= $request->input('state.id');
        } 
        if ($request->has('gender')) {
            $validatedData['gender']= $request->input('gender.name');
        } 
        if (isset($validatedData['birth_date'])) {
            $validatedData['birth_date'] = Carbon::parse($validatedData['birth_date'])->toDateString();
        }
        //upload files
        $photoPaths = [];
        if ($request->hasFile('photo_player.photo_path')) {
            // Store the file in the 'public/logos' directory            
            $photoPath = $request->file('photo_player.photo_path')->store('public/photo_player/photo_path');

            $photoPaths['photo_path'] = str_replace('public/', '/storage/', $photoPath);
        } 
        if ($request->hasFile('photo_player.photo_c_i')) {
            // Store the file in the 'public/logos' directory            
            $photoCI = $request->file('photo_player.photo_c_i')->store('public/photo_player/photo_c_i');

            $photoPaths['photo_c_i'] = str_replace('public/', '/storage/', $photoCI);
        } 
        if ($request->hasFile('photo_player.photo_birth_certificate')) {
            // Store the file in the 'public/logos' directory            
            $photoBC = $request->file('photo_player.photo_birth_certificate')->store('public/photo_player/photo_birth_certificate');

            $photoPaths['photo_birth_certificate'] = str_replace('public/', '/storage/', $photoBC);
        } 
        if ($request->hasFile('photo_player.photo_parental_authorization')) {
            // Store the file in the 'public/logos' directory            
            $photoPA = $request->file('photo_player.photo_parental_authorization')->store('public/photo_player/photo_parental_authorization');

            $photoPaths['photo_parental_authorization'] = str_replace('public/', '/storage/', $photoPA);
        }else {
            $photoPaths['photo_parental_authorization'] = "";
        }   

        $player = Player::create($validatedData);    
        if($request->hasFile('photo_player.photo_path') &&
        $request->hasFile('photo_player.photo_c_i') &&
        $request->hasFile('photo_player.photo_birth_certificate')){
            $player->photoPlayer()->create([
                'photo_path' => $photoPaths['photo_path'],
                'photo_c_i' => $photoPaths['photo_c_i'],
                'photo_birth_certificate' => $photoPaths['photo_birth_certificate'],
                'photo_parental_authorization' => $photoPaths['photo_parental_authorization'],
            ]);
        }
        //Creating log transactions
        $playerName = $player->first_name . ($player->second_name ? ' ' .$player->second_name : '' ). ' ' .$player->last_name . ' ' . ($player->mother_last_name ? $player->mother_last_name : '' );
        $playerState = $player->state == 1 ? 'Inhabilitado' : 'Habilitado';
        $details = 'Nombre: ' . $playerName . ', CI: ' . $player->c_i . ', Id de Equipo: ' . $player->team_id. ', Región de nacimiento: ' . $player->region_birth. ', Estado: '. $playerState. ', Nacionalidad: '. $player->nacionality;
        LogTransaction::create([
            'user_id' => auth()->id(),
            'action' => 'Crear', // HTTP method used for the request
            'resource' => 'Jugador', // Name of the resource being accessed
            'resource_id' => $player?->id, // ID of the resource
            'details' => $details, // Any additional details 
        ]);
        
        return redirect()->route('players.show', $player->id)->with('success', 'Jugador creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player): Response
    {

        $team = $player->team;
        $photoPlayer = $player->photoPlayer;
//        dd($photoPlayer);

        return Inertia::render('Admin/Players/Show', [
            'player' => new PlayerResource($player),
            'team' => $team,
            'photoPlayer' => $photoPlayer,
        ]); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player): Response
    {    
        $this->authorize('update', $player);
        $player->load('team.category')->get();
        //dd($player);
        $team = Team::with('category')->get();
      
        return Inertia::render('Admin/Players/Edit', [
            'player' => new PlayerResource($player),
            'team' => TeamResource::collection($team),
            'photoPlayer' => PhotoPlayerResource::collection(PhotoPlayer::all())
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerRequest $request, string $id):RedirectResponse
    {        
        $player = Player::find($id);            
        $this->authorize('update', $player);

        $validatedData = $request->validated();
        if ($request->has('team')) {
            $teamId = $request->input('team.id');
            $validatedData['team_id']= $teamId;
        } 
        if ($request->has('gender')) {
            $validatedData['gender']= $request->input('gender.name');
        } 
        if ($request->has('state')) {
            $validatedData['state']= $request->input('state.id');
        } 
        if (isset($validatedData['birth_date'])) {
            $validatedData['birth_date'] = Carbon::parse($validatedData['birth_date'])->toDateString();
        }
        // udpate files
        $photoPaths = [];

        $updateFile = function ($request, $file, $column) use ($player, &$photoPaths) {
            if ($request->hasFile("photo_player.$file")) {
                if ($player->photoPlayer && $player->photoPlayer->$column) {
                    $filename = basename($player->photoPlayer->$column);
                    Storage::delete("public/photo_player/$column/" . $filename);
                }

                // Store the file in the "public/photo_player" directory            
                $uploadedFile = $request->file("photo_player.$file")->store("public/photo_player/$column");

                $photoPaths[$column] = str_replace('public/', '/storage/', $uploadedFile);
            }
        };

        $columns = ['photo_path', 'photo_c_i', 'photo_birth_certificate', 'photo_parental_authorization'];

        foreach ($columns as $column) {
            $updateFile($request, $column, $column);
        }
        //
        $oldPlayerName = $player->first_name . ($player->second_name ? ' ' . $player->second_name : '') . ' ' . $player->last_name . ' ' . ($player->mother_last_name ? $player->mother_last_name : '');
        $oldPlayerState = $player->state == 1 ? 'Inhabilitado' : 'Habilitado';
        $oldPlayerCi = $player->c_i;
        $oldTeamId = $player->team_id;
        $oldRegionBirth = $player->region_birth;
        $oldNacionality = $player->nacionality;


        $player->update($validatedData);   

        if ($player->photoPlayer) {
            $player->photoPlayer()->update($photoPaths);
        } elseif(!$player->photoPlayer && $request->hasFile('photo_player.photo_path')) {
            $player->photoPlayer()->create($photoPaths);
        }

        //Creating log transactions
        $playerName = $player->first_name . ($player->second_name ? ' ' .$player->second_name : '' ). ' ' .$player->last_name . ' ' . ($player->mother_last_name ? $player->mother_last_name : '' );
        $playerState = $player->state == 1 ? 'Inhabilitado' : 'Habilitado';

        $details = '[Nombre: ' . $oldPlayerName . '. a: ' . $playerName . '], [CI: ' . $oldPlayerCi . '. a: ' . $player->c_i . '], [Id de Equipo: ' . $oldTeamId . '. a: ' . $player->team_id . '], [Región de nacimiento: ' . $oldRegionBirth . '. a: ' . $player->region_birth . '], [Estado: ' . $oldPlayerState . '. a: ' . $playerState . '], [Nacionalidad: ' . $oldNacionality . '. a: ' . $player->nacionality . ']';


        LogTransaction::create([
            'user_id' => auth()->id(),
            'action' => 'Actualizar', // HTTP method used for the request
            'resource' => 'Jugador', // Name of the resource being accessed
            'resource_id' => $player?->id, // ID of the resource
            'details' => $details, // Any additional details 
        ]);
        return to_route('players.show', $player->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player): RedirectResponse
    {
        $this->authorize('delete', $player);

        //destroy files of public
        if ($player->photoPlayer) {
            $filename = basename($player->photoPlayer->photo_path);
            Storage::delete('public/photo_player/photo_path/' . $filename);
            $filename = basename($player->photoPlayer->photo_c_i);
            Storage::delete('public/photo_player/photo_c_i/' . $filename);
            $filename = basename($player->photoPlayer->photo_birth_certificate);
            Storage::delete('public/photo_player/photo_birth_certificate/' . $filename);
            $filename = basename($player->photoPlayer->photo_parental_authorization);
            Storage::delete('public/photo_player/photo_parental_authorization/' . $filename);
        }
        //Creating log transactions
        $playerName = $player->first_name . ($player->second_name ? ' ' .$player->second_name : '' ). ' ' .$player->last_name . ' ' . ($player->mother_last_name ? $player->mother_last_name : '' );
        $playerState = $player->state == 1 ? 'Inhabilitado' : 'Habilitado';
        $details = 'Nombre: ' . $playerName . ', CI: ' . $player->c_i . ', Id de Equipo: ' . $player->team_id. ', Región de nacimiento: ' . $player->region_birth. ', Estado: '. $playerState;
        LogTransaction::create([
            'user_id' => auth()->id(),
            'action' => 'Eliminar', // HTTP method used for the request
            'resource' => 'Jugador', // Name of the resource being accessed
            'resource_id' => $player?->id, // ID of the resource
            'details' => $details, // Any additional details 
        ]);
        $player->delete();
        
        return to_route('players.index');
    }
}
