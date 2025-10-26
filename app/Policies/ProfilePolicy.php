<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;

class ProfilePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAbility('profiles:viewAny', ['admin', 'manager', 'user']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Profile $profile): bool
    {
        return $user->hasAbility('profiles:view', ['admin', 'manager'])
            || ($user->hasAbility('profiles:view', ['user']) && $user->id === $profile->user_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility('profiles:create', ['admin', 'manager', 'user']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Profile $profile): bool
    {
        return $user->hasAbility('profiles:update', ['admin', 'manager'])
            || ($user->hasAbility('profiles:update', ['user']) && $user->id === $profile->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Profile $profile): bool
    {
        return $user->hasAbility('profiles:delete', ['admin', 'manager'])
            || ($user->hasAbility('profiles:delete', ['user']) && $user->id === $profile->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Profile $profile): bool
    {
        return $user->hasAbility('profiles:restore', ['admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Profile $profile): bool
    {
        return $user->hasAbility('profiles:forceDelete', ['admin']);
    }
}
