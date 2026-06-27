<?php

namespace App\Policies\Companion;

use App\Models\Companion\CompanionReply;
use App\Models\User\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class CompanionReplyPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_companion_replies');
    }

    public function view(?Authenticatable $user, CompanionReply $reply): bool
    {
        return $user instanceof Admin && $user->can('view_companion_replies');
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_companion_replies');
    }

    public function update(Authenticatable $user, CompanionReply $reply): bool
    {
        return $user instanceof Admin && $user->can('edit_companion_replies');
    }

    public function delete(Authenticatable $user, CompanionReply $reply): bool
    {
        return $user instanceof Admin && $user->can('delete_companion_replies');
    }

    public function restore(Authenticatable $user, CompanionReply $reply): bool
    {
        return $user instanceof Admin && $user->can('restore_companion_replies');
    }

    public function forceDelete(Authenticatable $user, CompanionReply $reply): bool
    {
        return $user instanceof Admin && $user->can('force_delete_companion_replies');
    }
}
