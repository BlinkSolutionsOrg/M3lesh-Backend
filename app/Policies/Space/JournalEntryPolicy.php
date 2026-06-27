<?php

namespace App\Policies\Space;

use App\Models\Space\JournalEntry;
use App\Models\User\User;

class JournalEntryPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function view(User $user, JournalEntry $entry): bool
    {
        return $user->id === $entry->user_id;
    }

    public function delete(User $user, JournalEntry $entry): bool
    {
        return $user->id === $entry->user_id;
    }
}
