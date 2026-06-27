<?php

namespace App\Policies\Circle;

use App\Models\Circle\Circle;
use App\Models\Circle\CircleWin;
use App\Models\User\Admin;
use App\Models\User\User;
use Illuminate\Contracts\Auth\Authenticatable;

class CircleWinPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_circles');
    }

    public function view(?Authenticatable $user, CircleWin $win): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_circles');
        }

        return ! $win->trashed();
    }

    /**
     * A user posts a win to an active circle.
     */
    public function create(User $user, Circle $circle): bool
    {
        return $circle->is_active && ! $circle->trashed();
    }

    public function update(Authenticatable $user, CircleWin $win): bool
    {
        if ($user instanceof Admin) {
            return $user->can('edit_circles');
        }

        return $user instanceof User && $user->id === $win->user_id;
    }

    public function delete(Authenticatable $user, CircleWin $win): bool
    {
        if ($user instanceof Admin) {
            return $user->can('delete_circles');
        }

        return $user instanceof User && $user->id === $win->user_id;
    }

    public function restore(Authenticatable $user, CircleWin $win): bool
    {
        return $user instanceof Admin && $user->can('restore_circles');
    }

    public function forceDelete(Authenticatable $user, CircleWin $win): bool
    {
        return $user instanceof Admin && $user->can('force_delete_circles');
    }

    /**
     * A user cheers (🎉) another member's win.
     */
    public function cheer(User $user, CircleWin $win): bool
    {
        return ! $win->trashed();
    }
}
