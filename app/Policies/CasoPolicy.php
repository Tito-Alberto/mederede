<?php

namespace App\Policies;

use App\Models\Caso;
use App\Models\User;

class CasoPolicy
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
    public function view(User $user, Caso $caso): bool
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
    public function update(User $user, Caso $caso): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'profissional_saude') {
            return $caso->user_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Caso $caso): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Caso $caso): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Caso $caso): bool
    {
        return $user->role === 'admin';
    }
}
