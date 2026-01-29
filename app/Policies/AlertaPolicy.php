<?php

namespace App\Policies;

use App\Models\Alerta;
use App\Models\User;

class AlertaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'profissional_saude', 'publico'], true);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Alerta $alerta): bool
    {
        return in_array($user->role, ['admin', 'profissional_saude', 'publico'], true);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'profissional_saude'], true);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Alerta $alerta): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'profissional_saude') {
            return $alerta->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Alerta $alerta): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Alerta $alerta): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Alerta $alerta): bool
    {
        return $user->role === 'admin';
    }
}
