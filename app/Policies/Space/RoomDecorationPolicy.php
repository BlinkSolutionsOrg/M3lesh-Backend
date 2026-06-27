<?php

namespace App\Policies\Space;

use App\Models\Space\RoomDecoration;
use App\Models\User\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class RoomDecorationPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_room_decorations');
    }

    public function view(?Authenticatable $user, RoomDecoration $decoration): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_room_decorations');
        }

        return $decoration->is_active && ! $decoration->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_room_decorations');
    }

    public function update(Authenticatable $user, RoomDecoration $decoration): bool
    {
        return $user instanceof Admin && $user->can('edit_room_decorations');
    }

    public function delete(Authenticatable $user, RoomDecoration $decoration): bool
    {
        return $user instanceof Admin && $user->can('delete_room_decorations');
    }

    public function restore(Authenticatable $user, RoomDecoration $decoration): bool
    {
        return $user instanceof Admin && $user->can('restore_room_decorations');
    }

    public function forceDelete(Authenticatable $user, RoomDecoration $decoration): bool
    {
        return $user instanceof Admin && $user->can('force_delete_room_decorations');
    }
}
