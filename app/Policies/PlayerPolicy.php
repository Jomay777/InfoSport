<?php

namespace App\Policies;

use App\Models\Player;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PlayerPolicy
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
    public function view(User $user, Player $player)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo('Crear jugador') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Crear jugador") para realizar esta acción.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Player $player): Response
    {
        return $user->hasPermissionTo('Actualizar jugador') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Actualizar jugador") para realizar esta acción.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Player $player): Response
    {
        return $user->hasPermissionTo('Eliminar jugador') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Eliminar jugador") para realizar esta acción.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Player $player)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Player $player)
    {
        //
    }
}
