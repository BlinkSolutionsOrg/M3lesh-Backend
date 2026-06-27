<?php

namespace App\Policies\Space;

use App\Models\Space\DailyCardTip;
use App\Models\User\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class DailyCardTipPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('view_daily_card_tips');
    }

    public function view(?Authenticatable $user, DailyCardTip $tip): bool
    {
        if ($user instanceof Admin) {
            return $user->can('view_daily_card_tips');
        }

        return $tip->is_active && ! $tip->trashed();
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('create_daily_card_tips');
    }

    public function update(Authenticatable $user, DailyCardTip $tip): bool
    {
        return $user instanceof Admin && $user->can('edit_daily_card_tips');
    }

    public function delete(Authenticatable $user, DailyCardTip $tip): bool
    {
        return $user instanceof Admin && $user->can('delete_daily_card_tips');
    }

    public function restore(Authenticatable $user, DailyCardTip $tip): bool
    {
        return $user instanceof Admin && $user->can('restore_daily_card_tips');
    }

    public function forceDelete(Authenticatable $user, DailyCardTip $tip): bool
    {
        return $user instanceof Admin && $user->can('force_delete_daily_card_tips');
    }
}
