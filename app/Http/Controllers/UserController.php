<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\LogTransaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $users = User::query()
        ->latest()  
        ->take(20); 

        if ($request->search) {
            $users->where('users.id', 'like', $request->search)
                ->orWhere('users.name', 'like', '%' . $request->search . '%')
                ->orWhere('users.email', 'like', $request->search . '%');
        }

        $users = $users->get();
        return Inertia::render('Admin/Users/UserIndex', [
            'users' => UserResource::collection($users)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create', [
            'roles' => RoleResource::collection(Role::all()),
            'permissions' => PermissionResource::collection(Permission::all())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->syncRoles($request->input('roles.*.name'));
        $user->syncPermissions($request->input('permissions.*.name'));
        
        //Created LogTransactions
        $rolesIds = implode(',', $request->input('roles.*.id'));
        $permissionsIds = implode(',', $request->input('permissions.*.id'));
//        dd($rolesIds, $permissionsIds);

        $details = 'Nombre: ' . $user->name .', Correo Electrónico: ' . $user->email . ', Ids de Roles: ' . $rolesIds. ', Ids de Permisos: ' . $permissionsIds;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Crear', // HTTP method used for the request
            'resource' => 'Usuario', // Name of the resource being accessed
            'resource_id' => $user?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  
        return to_route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): Response
    {
        $user->load(['roles', 'permissions']);
        return Inertia::render('Admin/Users/Edit', [
            'user' => new UserResource($user),
            'roles' => RoleResource::collection(Role::all()),
            'permissions' => PermissionResource::collection(Permission::all())
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        //dd($request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|' . Rule::unique('users', 'email')->ignore($user),
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'roles' => ['sometimes', 'array'],
            'permissions' => ['sometimes', 'array']
        ]);
        //All Data
        $oldName = $user->name;
        $oldEmail = $user->email;
        $oldRolesIds = $user->roles->pluck('id')->toArray();
        $oldRolesIds = json_encode($oldRolesIds);
        $oldPermissionsIds = $user->permissions->pluck('id')->toArray();
        $oldPermissionsIds = json_encode($oldPermissionsIds);
        //dd($oldName, $oldEmail, $oldRolesIds, $oldPermissionsIds);
  

        if(!$request->password){
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        }       

        $user->syncRoles($request->input('roles.*.name'));
        $user->syncPermissions($request->input('permissions.*.name'));

        //Created LogTransactions
        $rolesIds = implode(',', $request->input('roles.*.id'));
        $permissionsIds = implode(',', $request->input('permissions.*.id'));
//      dd($rolesIds, $permissionsIds);

        $details = '[Nombre: ' . $oldName . '. a: ' . $user->name . '], [Correo Electrónico: ' . $oldEmail . '. a: ' . $user->email . '], [Ids de Roles: ' . $oldRolesIds . '. a: ' . $rolesIds . '], [Ids de Permisos: ' . $oldPermissionsIds . '. a: ' . $permissionsIds . ']';
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Actualizar', // HTTP method used for the request
            'resource' => 'Usuario', // Name of the resource being accessed
            'resource_id' => $user?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);  

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {   
        //Created LogTransactions
        $rolesIds = $user->roles->pluck('id')->toArray();
        $permissionsIds = $user->permissions->pluck('id')->toArray();

        $rolesIds = json_encode($rolesIds);
        $permissionsIds = json_encode($permissionsIds);
//        dd($rolesIds, $permissionsIds);

        $details = 'Nombre: ' . $user->name .', Correo Electrónico: ' . $user->email . ', Ids de Roles: ' . $rolesIds. ', Ids de Permisos: ' . $permissionsIds;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Eliminar', // HTTP method used for the request
            'resource' => 'Usuario', // Name of the resource being accessed
            'resource_id' => $user?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);
        $user->delete();
        return to_route('users.index');
    }
}