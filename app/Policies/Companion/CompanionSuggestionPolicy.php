<?php

namespace App\Policies\Companion;

use App\Models\Companion\CompanionSuggestion;
use App\Models\User\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class CompanionSuggestionPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_companion_suggestions');
    }

    public function view(?Authenticatable $user, CompanionSuggestion $suggestion): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_companion_suggestions');
        }

        return $suggestion->is_active && ! $suggestion->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_companion_suggestions');
    }

    public function update(Authenticatable $user, CompanionSuggestion $suggestion): bool
    {
        return $user instanceof Admin && $user->can('edit_companion_suggestions');
    }

    public function delete(Authenticatable $user, CompanionSuggestion $suggestion): bool
    {
        return $user instanceof Admin && $user->can('delete_companion_suggestions');
    }

    public function restore(Authenticatable $user, CompanionSuggestion $suggestion): bool
    {
        return $user instanceof Admin && $user->can('restore_companion_suggestions');
    }

    public function forceDelete(Authenticatable $user, CompanionSuggestion $suggestion): bool
    {
        return $user instanceof Admin && $user->can('force_delete_companion_suggestions');
    }
}
