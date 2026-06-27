<?php

namespace App\Policies\Help;

use App\Models\Help\HelpAsk;
use App\Models\Help\HelpReply;
use App\Models\User\Admin;
use App\Models\User\User;
use Illuminate\Contracts\Auth\Authenticatable;

class HelpReplyPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_help_asks');
    }

    public function view(?Authenticatable $user, HelpReply $reply): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_help_asks');
        }

        return ! $reply->trashed();
    }

    /**
     * A user replies to a non-trashed ask.
     */
    public function create(User $user, HelpAsk $ask): bool
    {
        return ! $ask->trashed();
    }

    public function update(Authenticatable $user, HelpReply $reply): bool
    {
        if ($user instanceof Admin) {
            return $user->can('edit_help_asks');
        }

        return $user instanceof User && $user->id === $reply->user_id;
    }

    public function delete(Authenticatable $user, HelpReply $reply): bool
    {
        if ($user instanceof Admin) {
            return $user->can('delete_help_asks');
        }

        return $user instanceof User && $user->id === $reply->user_id;
    }

    public function restore(Authenticatable $user, HelpReply $reply): bool
    {
        return $user instanceof Admin && $user->can('restore_help_asks');
    }

    public function forceDelete(Authenticatable $user, HelpReply $reply): bool
    {
        return $user instanceof Admin && $user->can('force_delete_help_asks');
    }

    /**
     * A user upvotes ("فادني") a reply.
     */
    public function vote(User $user, HelpReply $reply): bool
    {
        return ! $reply->trashed();
    }
}
