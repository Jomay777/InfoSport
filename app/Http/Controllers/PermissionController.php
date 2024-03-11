<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\LogTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $permission = Permission::query()
        ->latest()
        ->take(20); 
        if ($request->search) {
            $permission->where('permissions.id', 'like', '%' . $request->search . '%')
                ->orWhere('permissions.name', 'like', '%' . $request->search . '%');
        }
        $permission = $permission->get();
        return Inertia::render('Admin/Permissions/PermissionIndex',[
            'permissions' =>  PermissionResource::collection($permission)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Permissions/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePermissionRequest $request): RedirectResponse
    {
        $permission = Permission::create($request->validated());
        //Created LogTransactions
        $details = 'Nombre: ' . $permission->name;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Crear', // HTTP method used for the request
            'resource' => 'Permiso', // Name of the resource being accessed
            'resource_id' => $permission?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
        return to_route('permissions.index');
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
    public function edit(Permission $permission): Response
    {
        return Inertia::render('Admin/Permissions/Edit', [
            'permission' => new PermissionResource($permission)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        $oldName = $permission->name;
        
        //update permission
        $permission->update($request->validated());
        //created log transaction
        $details = '[Nombre: ' . $permission->name. ', a:'. $oldName.']';
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Actualizar', // HTTP method used for the request
            'resource' => 'Permiso', // Name of the resource being accessed
            'resource_id' => $permission?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
        return to_route('permissions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //Created LogTransactions
        $details = 'Nombre: ' . $permission->name;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Eliminar', // HTTP method used for the request
            'resource' => 'Permiso', // Name of the resource being accessed
            'resource_id' => $permission?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
        $permission->delete();
        return back();
    }
}
