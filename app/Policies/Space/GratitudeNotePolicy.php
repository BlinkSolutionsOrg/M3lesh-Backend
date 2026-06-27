<?php

namespace App\Policies\Space;

use App\Models\Space\GratitudeNote;
use App\Models\User\User;

class GratitudeNotePolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, GratitudeNote $note): bool
    {
        return $user->id === $note->user_id;
    }

    public function delete(User $user, GratitudeNote $note): bool
    {
        return $user->id === $note->user_id;
    }
}
