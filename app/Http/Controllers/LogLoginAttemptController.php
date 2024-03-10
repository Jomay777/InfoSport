<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogLoginAttemptResource;
use App\Models\LogLoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Inertia\Response;

class LogLoginAttemptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $logLoginAttempt = LogLoginAttempt::all()->load('user');

        //dd($logLoginAttempt);
        return Inertia::render('Admin/AllLogs/AllLogsIndex', [
            'log_login_attempt' => LogLoginAttemptResource::collection($logLoginAttempt),
            'search' => $request->search, 
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show(LogLoginAttempt $logLoginAttempt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LogLoginAttempt $logLoginAttempt)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LogLoginAttempt $logLoginAttempt)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LogLoginAttempt $logLoginAttempt)
    {
        //
    }
}
