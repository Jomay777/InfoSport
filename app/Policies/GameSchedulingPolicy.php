<?php

namespace App\Policies;

use App\Models\GameScheduling;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GameSchedulingPolicy
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
    public function view(User $user, GameScheduling $gameScheduling)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo('Crear programación de partido') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Crear programación de partido") para realizar esta acción.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GameScheduling $gameScheduling): Response
    {
        return $user->hasPermissionTo('Actualizar programación de partido') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Actualizar programación de partido") para realizar esta acción.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GameScheduling $gameScheduling): Response
    {
        return $user->hasPermissionTo('Eliminar programación de partido') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Eliminar programación de partido") para realizar esta acción.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GameScheduling $gameScheduling)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GameScheduling $gameScheduling)
    {
        //
    }
}
