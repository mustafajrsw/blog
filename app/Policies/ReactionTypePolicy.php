<?php

namespace App\Policies;

use App\Models\ReactionType;
use App\Models\User;

class ReactionTypePolicy
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
        return $user->hasAbility('reaction-types:viewAny', ['admin', 'manager', 'user']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ReactionType $reactionType): bool
    {
        return $user->hasAbility('reaction-types:view', ['admin', 'manager'])
            || ($user->hasAbility('reaction-types:view', ['user']) && $user->id === $reactionType->user_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAbility('reaction-types:create', ['admin', 'manager', 'user']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ReactionType $reactionType): bool
    {
        return $user->hasAbility('reaction-types:update', ['admin', 'manager'])
            || ($user->hasAbility('reaction-types:update', ['user']) && $user->id === $reactionType->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ReactionType $reactionType): bool
    {
        return $user->hasAbility('reaction-types:delete', ['admin', 'manager'])
            || ($user->hasAbility('reaction-types:delete', ['user']) && $user->id === $reactionType->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ReactionType $reactionType): bool
    {
        return $user->hasAbility('reaction-types:restore', ['admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ReactionType $reactionType): bool
    {
        return $user->hasAbility('reaction-types:forceDelete', ['admin']);
    }
}
