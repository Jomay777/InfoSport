<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\LogTransaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $roles = Role::query()
        ->latest()
        ->take(20); 
        if ($request->search) {
            $roles->where('roles.id', 'like', $request->search )
                ->orWhere('roles.name', 'like', '%' . $request->search . '%');
        }
        $roles = $roles->get();
        return Inertia::render('Admin/Roles/RoleIndex', [
            'roles' => RoleResource::collection($roles)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Roles/Create', [
            'permissions' => PermissionResource::collection(Permission::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request)
    {
        $role = Role::create(['name' => $request->name]);
        if ($request->has('permissions')) {
            $role->syncPermissions($request->input('permissions.*.name'));
        }
        //Created LogTransactions
        $permissionsIds = implode(',', $request->input('permissions.*.id'));
//        dd($rolesIds, $permissionsIds);

        $details = 'Nombre: ' . $role->name . ', Ids de Permisos: ' . $permissionsIds;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Crear', // HTTP method used for the request
            'resource' => 'Rol', // Name of the resource being accessed
            'resource_id' => $role?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
        return to_route('roles.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findById($id);
        $role->load('permissions');

        return Inertia::render('Admin/Roles/Edit', [
            'role' => new RoleResource($role),
            'permissions' => PermissionResource::collection(Permission::all())
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRoleRequest $request, string $id)
    {
        $role = Role::findById($id);
        //Old Data
        $oldName= $role->name;
        $oldPerimissionsIds = $role->permissions->pluck('id')->toArray() ;
        $oldPerimissionsIds = json_encode($oldPerimissionsIds);
        //dd($oldName, $oldPerimissionsIds);
        //update Role
        $role->update([
            'name' => $request->name
        ]);
        $role->syncPermissions($request->input('permissions.*.name'));

        //Created LogTransactions
        $permissionsIds = implode(',', $request->input('permissions.*.id'));
//        dd($rolesIds, $permissionsIds);

        $details = '[Nombre: ' . $oldName . '. a:'. $role->name . '], [Ids de Permisos: ' . $oldPerimissionsIds . '. a:'. $permissionsIds. ']';
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Actualizar', // HTTP method used for the request
            'resource' => 'Rol', // Name of the resource being accessed
            'resource_id' => $role?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
                 

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::findById($id);
        //Created LogTransactions
        $permissionsIds = $role->permissions->pluck('id')->toArray();

        $permissionsIds = json_encode($permissionsIds);
        //dd( $permissionsIds);

        $details = 'Nombre: ' . $role->name .', Ids de Permisos: ' . $permissionsIds;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Eliminar', // HTTP method used for the request
            'resource' => 'Rol', // Name of the resource being accessed
            'resource_id' => $role?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);
        $role->delete();
        return back();
    }
}