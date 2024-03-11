<?php

namespace App\Http\Controllers;

use App\Http\Resources\LogLoginAttemptResource;
use App\Http\Resources\LogTransactionResource;
use App\Models\LogLoginAttempt;
use App\Models\LogTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $logLoginAttempt = LogLoginAttempt::all()->load('user');
        $logTransactions = LogTransaction::all()->load('user');
        //dd($logTransactions);
        //dd($logLoginAttempt);
        return Inertia::render('Admin/AllLogs/AllLogsIndex', [
            'log_login_attempt' => LogLoginAttemptResource::collection($logLoginAttempt),
            'log_transactions' => LogTransactionResource::collection($logTransactions),
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
