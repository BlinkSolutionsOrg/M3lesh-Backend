<?php

namespace App\Policies\Circle;

use App\Models\Circle\Circle;
use App\Models\User\Admin;
use App\Models\User\User;
use Illuminate\Contracts\Auth\Authenticatable;

class CirclePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_circles');
    }

    public function view(?Authenticatable $user, Circle $circle): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_circles');
        }

        return $circle->is_active && ! $circle->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_circles');
    }

    public function update(Authenticatable $user, Circle $circle): bool
    {
        return $user instanceof Admin && $user->can('edit_circles');
    }

    public function delete(Authenticatable $user, Circle $circle): bool
    {
        return $user instanceof Admin && $user->can('delete_circles');
    }

    public function restore(Authenticatable $user, Circle $circle): bool
    {
        return $user instanceof Admin && $user->can('restore_circles');
    }

    public function forceDelete(Authenticatable $user, Circle $circle): bool
    {
        return $user instanceof Admin && $user->can('force_delete_circles');
    }

    /**
     * A user joins an active circle.
     */
    public function join(User $user, Circle $circle): bool
    {
        return $circle->is_active && ! $circle->trashed();
    }

    /**
     * A user may always leave a circle they are in.
     */
    public function leave(User $user, Circle $circle): bool
    {
        return true;
    }
}
