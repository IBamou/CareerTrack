<?php

namespace App\Policies;

use App\Models\Reminder;
use App\Models\User;

class ReminderPolicy
{
    public function view(User $user, Reminder $reminder): bool
    {
        return $user->is($reminder->user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Reminder $reminder): bool
    {
        return $user->is($reminder->user);
    }

    public function delete(User $user, Reminder $reminder): bool
    {
        return $user->is($reminder->user);
    }
}
