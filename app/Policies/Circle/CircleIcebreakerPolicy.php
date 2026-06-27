<?php

namespace App\Policies\Circle;

use App\Models\Circle\CircleIcebreaker;
use App\Models\User\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class CircleIcebreakerPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_circles');
    }

    public function view(?Authenticatable $user, CircleIcebreaker $icebreaker): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_circles');
        }

        return $icebreaker->is_active && ! $icebreaker->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_circles');
    }

    public function update(Authenticatable $user, CircleIcebreaker $icebreaker): bool
    {
        return $user instanceof Admin && $user->can('edit_circles');
    }

    public function delete(Authenticatable $user, CircleIcebreaker $icebreaker): bool
    {
        return $user instanceof Admin && $user->can('delete_circles');
    }

    public function restore(Authenticatable $user, CircleIcebreaker $icebreaker): bool
    {
        return $user instanceof Admin && $user->can('restore_circles');
    }

    public function forceDelete(Authenticatable $user, CircleIcebreaker $icebreaker): bool
    {
        return $user instanceof Admin && $user->can('force_delete_circles');
    }
}
