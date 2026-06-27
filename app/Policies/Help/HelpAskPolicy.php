<?php

namespace App\Policies\Help;

use App\Models\Help\HelpAsk;
use App\Models\User\Admin;
use App\Models\User\User;
use Illuminate\Contracts\Auth\Authenticatable;

class HelpAskPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_help_asks');
    }

    public function view(?Authenticatable $user, HelpAsk $ask): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_help_asks');
        }

        return ! $ask->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Admin) {
            return $user->can('create_help_asks');
        }

        return $user instanceof User;
    }

    public function update(Authenticatable $user, HelpAsk $ask): bool
    {
        return $user instanceof Admin && $user->can('edit_help_asks');
    }

    public function delete(Authenticatable $user, HelpAsk $ask): bool
    {
        if ($user instanceof Admin) {
            return $user->can('delete_help_asks');
        }

        return $user instanceof User && $user->id === $ask->user_id;
    }

    public function restore(Authenticatable $user, HelpAsk $ask): bool
    {
        return $user instanceof Admin && $user->can('restore_help_asks');
    }

    public function forceDelete(Authenticatable $user, HelpAsk $ask): bool
    {
        return $user instanceof Admin && $user->can('force_delete_help_asks');
    }
}
