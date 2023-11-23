<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClubResource;
use App\Models\Club;
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
}
