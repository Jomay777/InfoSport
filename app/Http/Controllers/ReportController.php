<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClubResource;
use App\Http\Resources\TeamResource;
use App\Http\Resources\UserResource;
use App\Models\Club;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clubs = Club::with('users');
        $clubs = $clubs->get();
        $users = User::with('clubs');
        $users = $users->get();
        $teams = Team::with('category', 'club');
        $teams = $teams->get();
        //dd($teams);

        return Inertia::render('Admin/ReportIndex', [
            'clubs' => ClubResource::collection($clubs),
            'users' => UserResource::collection($users),
            'teams' => TeamResource::collection($teams),


        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
