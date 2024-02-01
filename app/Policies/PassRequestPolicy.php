<?php

namespace App\Policies;

use App\Models\PassRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PassRequestPolicy
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
    public function view(User $user, PassRequest $passRequest)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo('Crear pase') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Crear pase") para realizar esta acción.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PassRequest $passRequest): Response
    {
        return $user->hasPermissionTo('Actualizar pase') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Actualizar pase") para realizar esta acción.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PassRequest $passRequest): Response
    {
        return $user->hasPermissionTo('Eliminar pase') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Eliminar pase") para realizar esta acción.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PassRequest $passRequest)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PassRequest $passRequest)
    {
        //
    }
}
