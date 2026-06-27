<?php

namespace App\Policies\Community;

use App\Models\Community\CommunitySeason;
use App\Models\User\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class CommunitySeasonPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_community_seasons');
    }

    public function view(?Authenticatable $user, CommunitySeason $season): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_community_seasons');
        }

        // Users may view an active, non-deleted season.
        return $season->is_active && ! $season->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_community_seasons');
    }

    public function update(Authenticatable $user, CommunitySeason $season): bool
    {
        return $user instanceof Admin && $user->can('edit_community_seasons');
    }

    public function delete(Authenticatable $user, CommunitySeason $season): bool
    {
        return $user instanceof Admin && $user->can('delete_community_seasons');
    }

    public function restore(Authenticatable $user, CommunitySeason $season): bool
    {
        return $user instanceof Admin && $user->can('restore_community_seasons');
    }

    public function forceDelete(Authenticatable $user, CommunitySeason $season): bool
    {
        return $user instanceof Admin && $user->can('force_delete_community_seasons');
    }
}
