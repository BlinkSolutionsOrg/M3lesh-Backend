<?php

namespace App\Policies\Space;

use App\Models\Space\Achievement;
use App\Models\User\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class AchievementPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_achievements');
    }

    public function view(?Authenticatable $user, Achievement $achievement): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_achievements');
        }

        return $achievement->is_active && ! $achievement->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_achievements');
    }

    public function update(Authenticatable $user, Achievement $achievement): bool
    {
        return $user instanceof Admin && $user->can('edit_achievements');
    }

    public function delete(Authenticatable $user, Achievement $achievement): bool
    {
        return $user instanceof Admin && $user->can('delete_achievements');
    }

    public function restore(Authenticatable $user, Achievement $achievement): bool
    {
        return $user instanceof Admin && $user->can('restore_achievements');
    }

    public function forceDelete(Authenticatable $user, Achievement $achievement): bool
    {
        return $user instanceof Admin && $user->can('force_delete_achievements');
    }
}
