<?php

namespace App\Policies;

use App\Models\Tournament;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TournamentPolicy
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
    public function view(User $user, Tournament $tournament)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasPermissionTo('Crear torneo') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Crear torneo") para realizar esta acción.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tournament $tournament): Response
    {
        return $user->hasPermissionTo('Actualizar torneo') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Actualizar torneo") para realizar esta acción.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tournament $tournament): Response
    {
        return $user->hasPermissionTo('Eliminar torneo') ?  Response::allow()
        : Response::deny('No tienes el permiso ("Eliminar torneo") para realizar esta acción.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tournament $tournament)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tournament $tournament)
    {
        //
    }
}
