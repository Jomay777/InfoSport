<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameRoleController;
use App\Http\Controllers\GameSchedulingController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PassRequestController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\PhotoPlayerController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\PlayerSanctionController;
use App\Http\Controllers\PositionTableController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublishedTournamentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RevokePermissionFromRoleController;
use App\Http\Controllers\RevokePermissionFromUserController;
use App\Http\Controllers\RemoveRoleFromUserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TeamSanctionController;
use App\Http\Controllers\TournamentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {   
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('players/{player}/pdf', [PlayerController::class, 'pdf'])->name('players.pdf')->middleware('role:Administrador|Comité técnico|Delegado');

//Routes for photo_players
Route::resource('/photo_players', PhotoPlayerController::class);
Route::get('photo_players/{photo_player}', [PhotoPlayerController::class, 'show'])->name('photo_players.show');

//adminstration panel
Route::get('/administration', [AdminController::class, 'index'])->name('administration.index');

//Route for dashoard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
//Route for tournament published
Route::get('/published_tournaments', [PublishedTournamentController::class, 'index'])
    ->name('published_tournaments');

//Route for tournament published
Route::get('/position_tables', [PositionTableController::class, 'index'])
->name('position_tables');

//Route for logLogginAttempts
Route::get('/logs', [LogController::class, 'index'])
->name('logs.index');
//Route for Helps
Route::get('/helps', [HelpController::class, 'index'])
->name('helps.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::middleware(['auth', 'role:Administrador'])->prefix('/admin')->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/permissions', PermissionController::class);

    Route::delete('/roles/{role}/permissions/{permission}', RevokePermissionFromRoleController::class)
        ->name('roles.permissions.destroy');
    Route::delete('/users/{user}/permissions/{permission}', RevokePermissionFromUserController::class)
        ->name('users.permissions.destroy');
    Route::delete('/users/{user}/roles/{role}', RemoveRoleFromUserController::class)
        ->name('users.roles.destroy');       
});
Route::middleware(['auth', 'can:Ver club'])->group(function () {

    //Routes for clubs
    Route::resource('/clubs', ClubController::class);
    Route::get('clubs/{club}', [ClubController::class, 'show'])->name('clubs.show');
});   
Route::middleware(['auth', 'can:Ver equipo'])->group(function () {

    //Routes for teams
    Route::resource('/teams', TeamController::class);
    Route::get('teams/{team}', [TeamController::class, 'show'])->name('teams.show');
});
Route::middleware(['auth', 'can:Ver jugador'])->group(function () {
    //Routes for players
    Route::resource('/players', PlayerController::class);
    Route::get('players/{player}', [PlayerController::class, 'show'])->name('players.show');
});
Route::middleware(['auth', 'can:Ver pase'])->group(function () {
    //Routes for pass_requests
    Route::resource('/pass_requests', PassRequestController::class);
    Route::get('pass_requests/{pass_request}', [PassRequestController::class, 'show'])->name('pass_requests.show');    
});

Route::middleware(['auth', 'can:Ver torneo'])->group(function () {
    //Routes for tournaments
    Route::resource('/tournaments', TournamentController::class);
    Route::get('tournaments/{tournament}', [TournamentController::class, 'show'])->name('tournaments.show');
});
Route::middleware(['auth', 'can:Ver rol de partido'])->group(function () {

    //Routes for game_roles
    Route::resource('/game_roles', GameRoleController::class);
    Route::get('game_roles/{game_role}', [GameRoleController::class, 'show'])->name('game_roles.show');
    Route::get('game_roles/{game_role}/publish', [GameRoleController::class, 'publish'])->name('game_roles.publish');
});
Route::middleware(['auth', 'can:Ver programación de partido'])->group(function () {
    //Routes for game_schedulings
    Route::resource('/game_schedulings', GameSchedulingController::class);
    Route::get('game_schedulings/{game_scheduling}', [GameSchedulingController::class, 'show'])->name('game_schedulings.show');
});
Route::middleware(['auth', 'can:Ver partido'])->group(function () {
    //Routes for games
    Route::resource('/games', GameController::class);
    Route::get('games/{game}', [GameController::class, 'show'])->name('games.show');
});
//Routes for categories
Route::resource('/categories', CategoryController::class)->middleware(['auth','can:Ver categoría']);
//Routes for reports
//Route::resource('/reports', ReportController::class);
Route::middleware(['auth', 'can:Reportes'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])
    ->name('reports.index');
    Route::get('reports/users', [ReportController::class, 'user'])->name('reports.user');
    Route::get('reports/clubs', [ReportController::class, 'club'])->name('reports.club');
    Route::get('reports/teams', [ReportController::class, 'team'])->name('reports.team');
    Route::get('reports/players', [ReportController::class, 'player'])->name('reports.player');
    Route::get('reports/pass_requests', [ReportController::class, 'passRequest'])->name('reports.passRequest');
    Route::get('reports/categories', [ReportController::class, 'category'])->name('reports.category');
    Route::get('reports/tournaments', [ReportController::class, 'tournament'])->name('reports.tournament');
    Route::get('reports/game_roles', [ReportController::class, 'gameRole'])->name('reports.gameRole');
    Route::get('reports/game_schedulings', [ReportController::class, 'gameScheduling'])->name('reports.gameScheduling');
    Route::get('reports/games', [ReportController::class, 'game'])->name('reports.game');
    Route::get('reports/player_sanctions', [ReportController::class, 'playerSanction'])->name('reports.playerSanction');
    Route::get('reports/team_sanctions', [ReportController::class, 'teamSanction'])->name('reports.teamSanction');
    
});

//Routes for player_sanctions
Route::resource('/player_sanctions', PlayerSanctionController::class);
//Routes for team_sanctions
Route::resource('/team_sanctions', TeamSanctionController::class);

require __DIR__.'/auth.php';
