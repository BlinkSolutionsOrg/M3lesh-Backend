<?php

namespace App\Policies\Wheel;

use App\Models\User\Admin;
use App\Models\User\User;
use App\Models\Wheel\WheelResponse;
use Illuminate\Contracts\Auth\Authenticatable;

class WheelResponsePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_wheel_challenges');
    }

    public function view(?Authenticatable $user, WheelResponse $response): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_wheel_challenges');
        }

        return ! $response->trashed();
    }

    public function update(Authenticatable $user, WheelResponse $response): bool
    {
        if ($user instanceof Admin) {
            return $user->can('edit_wheel_challenges');
        }

        return $user instanceof User && $user->id === $response->user_id;
    }

    public function delete(Authenticatable $user, WheelResponse $response): bool
    {
        if ($user instanceof Admin) {
            return $user->can('delete_wheel_challenges');
        }

        return $user instanceof User && $user->id === $response->user_id;
    }

    public function restore(Authenticatable $user, WheelResponse $response): bool
    {
        return $user instanceof Admin && $user->can('restore_wheel_challenges');
    }

    public function forceDelete(Authenticatable $user, WheelResponse $response): bool
    {
        return $user instanceof Admin && $user->can('force_delete_wheel_challenges');
    }

    /**
     * A user reacts (😂 / ❤️) to a wheel response.
     */
    public function react(User $user, WheelResponse $response): bool
    {
        return ! $response->trashed();
    }
}
