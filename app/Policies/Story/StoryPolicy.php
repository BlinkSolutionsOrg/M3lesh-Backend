<?php

namespace App\Policies\Story;

use App\Models\Story\Story;
use App\Models\User\Admin;
use App\Models\User\User;
use Illuminate\Contracts\Auth\Authenticatable;

class StoryPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_stories');
    }

    public function view(?Authenticatable $user, Story $story): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_stories');
        }

        return ! $story->trashed();
    }

    /**
     * A user shares a story to the global feed.
     */
    public function create(User $user): bool
    {
        return true;
    }

    public function update(Authenticatable $user, Story $story): bool
    {
        if ($user instanceof Admin) {
            return $user->can('edit_stories');
        }

        return $user instanceof User && $user->id === $story->user_id;
    }

    public function delete(Authenticatable $user, Story $story): bool
    {
        if ($user instanceof Admin) {
            return $user->can('delete_stories');
        }

        return $user instanceof User && $user->id === $story->user_id;
    }

    public function restore(Authenticatable $user, Story $story): bool
    {
        return $user instanceof Admin && $user->can('restore_stories');
    }

    public function forceDelete(Authenticatable $user, Story $story): bool
    {
        return $user instanceof Admin && $user->can('force_delete_stories');
    }

    /**
     * A user hearts (💜) a story.
     */
    public function heart(User $user, Story $story): bool
    {
        return ! $story->trashed();
    }
}
