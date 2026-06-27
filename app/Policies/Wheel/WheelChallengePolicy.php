<?php

namespace App\Policies\Wheel;

use App\Models\User\Admin;
use App\Models\User\User;
use App\Models\Wheel\WheelChallenge;
use Illuminate\Contracts\Auth\Authenticatable;

class WheelChallengePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_wheel_challenges');
    }

    public function view(?Authenticatable $user, WheelChallenge $challenge): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_wheel_challenges');
        }

        return $challenge->is_active && ! $challenge->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_wheel_challenges');
    }

    public function update(Authenticatable $user, WheelChallenge $challenge): bool
    {
        return $user instanceof Admin && $user->can('edit_wheel_challenges');
    }

    public function delete(Authenticatable $user, WheelChallenge $challenge): bool
    {
        return $user instanceof Admin && $user->can('delete_wheel_challenges');
    }

    public function restore(Authenticatable $user, WheelChallenge $challenge): bool
    {
        return $user instanceof Admin && $user->can('restore_wheel_challenges');
    }

    public function forceDelete(Authenticatable $user, WheelChallenge $challenge): bool
    {
        return $user instanceof Admin && $user->can('force_delete_wheel_challenges');
    }

    /**
     * A user spins the active wheel.
     */
    public function spin(User $user, WheelChallenge $challenge): bool
    {
        return $challenge->is_active && ! $challenge->trashed();
    }

    /**
     * A user posts a reply to the active wheel challenge.
     */
    public function respond(User $user, WheelChallenge $challenge): bool
    {
        return $challenge->is_active && ! $challenge->trashed();
    }

    /**
     * A user posts a message in the active wheel's room.
     */
    public function message(User $user, WheelChallenge $challenge): bool
    {
        return $challenge->is_active && ! $challenge->trashed();
    }
}
