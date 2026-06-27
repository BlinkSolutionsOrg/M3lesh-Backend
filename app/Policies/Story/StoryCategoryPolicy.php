<?php

namespace App\Policies\Story;

use App\Models\Story\StoryCategory;
use App\Models\User\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class StoryCategoryPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_stories');
    }

    public function view(?Authenticatable $user, StoryCategory $category): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_stories');
        }

        return $category->is_active && ! $category->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_stories');
    }

    public function update(Authenticatable $user, StoryCategory $category): bool
    {
        return $user instanceof Admin && $user->can('edit_stories');
    }

    public function delete(Authenticatable $user, StoryCategory $category): bool
    {
        return $user instanceof Admin && $user->can('delete_stories');
    }

    public function restore(Authenticatable $user, StoryCategory $category): bool
    {
        return $user instanceof Admin && $user->can('restore_stories');
    }

    public function forceDelete(Authenticatable $user, StoryCategory $category): bool
    {
        return $user instanceof Admin && $user->can('force_delete_stories');
    }
}
