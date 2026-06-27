<?php

namespace App\Policies\Circle;

use App\Models\Circle\CircleChallenge;
use App\Models\User\Admin;
use App\Models\User\User;
use Illuminate\Contracts\Auth\Authenticatable;

class CircleChallengePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_circle_challenges');
    }

    public function view(?Authenticatable $user, CircleChallenge $challenge): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_circle_challenges');
        }

        return $challenge->is_active && ! $challenge->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_circle_challenges');
    }

    public function update(Authenticatable $user, CircleChallenge $challenge): bool
    {
        return $user instanceof Admin && $user->can('edit_circle_challenges');
    }

    public function delete(Authenticatable $user, CircleChallenge $challenge): bool
    {
        return $user instanceof Admin && $user->can('delete_circle_challenges');
    }

    public function restore(Authenticatable $user, CircleChallenge $challenge): bool
    {
        return $user instanceof Admin && $user->can('restore_circle_challenges');
    }

    public function forceDelete(Authenticatable $user, CircleChallenge $challenge): bool
    {
        return $user instanceof Admin && $user->can('force_delete_circle_challenges');
    }

    /**
     * A user toggles completion of a step in an active challenge.
     */
    public function completeStep(User $user, CircleChallenge $challenge): bool
    {
        return $challenge->is_active && ! $challenge->trashed();
    }

    /**
     * A user posts an encouragement message in an active challenge.
     */
    public function postMessage(User $user, CircleChallenge $challenge): bool
    {
        return $challenge->is_active && ! $challenge->trashed();
    }
}
