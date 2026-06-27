<?php

namespace App\Policies\Mood;

use App\Models\Mood\Mood;
use App\Models\User\Admin;
use App\Models\User\User;
use Illuminate\Contracts\Auth\Authenticatable;

class MoodPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_moods');
    }

    public function view(?Authenticatable $user, Mood $mood): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_moods');
        }

        return $mood->is_active && ! $mood->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_moods');
    }

    public function update(Authenticatable $user, Mood $mood): bool
    {
        return $user instanceof Admin && $user->can('edit_moods');
    }

    public function delete(Authenticatable $user, Mood $mood): bool
    {
        return $user instanceof Admin && $user->can('delete_moods');
    }

    public function restore(Authenticatable $user, Mood $mood): bool
    {
        return $user instanceof Admin && $user->can('restore_moods');
    }

    public function forceDelete(Authenticatable $user, Mood $mood): bool
    {
        return $user instanceof Admin && $user->can('force_delete_moods');
    }
}
