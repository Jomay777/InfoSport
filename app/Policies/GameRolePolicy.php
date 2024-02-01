<?php

namespace App\Policies;

use App\Models\GameRole;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GameRolePolicy
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
    public function view(User $user, GameRole $gameRole)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo('Crear rol de partido') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Crear rol de partido") para realizar esta acción.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GameRole $gameRole): Response
    {
        return $user->hasPermissionTo('Actualizar rol de partido') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Actualizar rol de partido") para realizar esta acción.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GameRole $gameRole): Response
    {
        return $user->hasPermissionTo('Eliminar rol de partido') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Eliminar rol de partido") para realizar esta acción.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GameRole $gameRole)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GameRole $gameRole)
    {
        //
    }
}
