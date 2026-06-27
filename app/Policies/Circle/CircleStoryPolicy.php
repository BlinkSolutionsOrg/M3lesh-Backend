<?php

namespace App\Policies\Circle;

use App\Models\Circle\Circle;
use App\Models\Circle\CircleStory;
use App\Models\User\Admin;
use App\Models\User\User;
use Illuminate\Contracts\Auth\Authenticatable;

class CircleStoryPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_circles');
    }

    public function view(?Authenticatable $user, CircleStory $story): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_circles');
        }

        return ! $story->trashed();
    }

    /**
     * A user shares a story in an active circle.
     */
    public function create(User $user, Circle $circle): bool
    {
        return $circle->is_active && ! $circle->trashed();
    }

    public function update(Authenticatable $user, CircleStory $story): bool
    {
        if ($user instanceof Admin) {
            return $user->can('edit_circles');
        }

        return $user instanceof User && $user->id === $story->user_id;
    }

    public function delete(Authenticatable $user, CircleStory $story): bool
    {
        if ($user instanceof Admin) {
            return $user->can('delete_circles');
        }

        return $user instanceof User && $user->id === $story->user_id;
    }

    public function restore(Authenticatable $user, CircleStory $story): bool
    {
        return $user instanceof Admin && $user->can('restore_circles');
    }

    public function forceDelete(Authenticatable $user, CircleStory $story): bool
    {
        return $user instanceof Admin && $user->can('force_delete_circles');
    }

    /**
     * A user hearts (💜) a story.
     */
    public function heart(User $user, CircleStory $story): bool
    {
        return ! $story->trashed();
    }
}
