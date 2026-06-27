<?php

namespace App\Policies\Companion;

use App\Models\Companion\CompanionSetting;
use App\Models\User\Admin;
use Illuminate\Contracts\Auth\Authenticatable;

class CompanionSettingsPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('manage_companion_settings');
    }

    public function view(?Authenticatable $user, CompanionSetting $setting): bool
    {
        return $user instanceof Admin && $user->can('manage_companion_settings');
    }

    public function update(Authenticatable $user, CompanionSetting $setting): bool
    {
        return $user instanceof Admin && $user->can('manage_companion_settings');
    }

    public function manage(Authenticatable $user): bool
    {
        return $user instanceof Admin && $user->can('manage_companion_settings');
    }
}
