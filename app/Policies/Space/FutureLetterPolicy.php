<?php

namespace App\Policies\Space;

use App\Models\Space\FutureLetter;
use App\Models\User\User;

class FutureLetterPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, FutureLetter $letter): bool
    {
        return $user->id === $letter->user_id;
    }

    public function open(User $user, FutureLetter $letter): bool
    {
        return $user->id === $letter->user_id;
    }

    public function delete(User $user, FutureLetter $letter): bool
    {
        return $user->id === $letter->user_id;
    }
}
