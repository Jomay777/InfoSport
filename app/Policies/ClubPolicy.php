<?php

namespace App\Policies;

use App\Models\Club;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClubPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo('Crear club') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Crear club") para realizar esta acción.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Club $club): Response
    {
        return $user->hasPermissionTo('Actualizar club') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Actualizar club") para realizar esta acción.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Club $club): Response
    {
        return $user->hasPermissionTo('Eliminar club') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Eliminar club") para realizar esta acción.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Club $club)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Club $club)
    {
        //
    }
}
