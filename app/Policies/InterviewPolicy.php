<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;

class InterviewPolicy
{
    public function view(User $user, Interview $interview): bool
    {
        return $user->is($interview->user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Interview $interview): bool
    {
        return $user->is($interview->user);
    }

    public function delete(User $user, Interview $interview): bool
    {
        return $user->is($interview->user);
    }

    public function archive(User $user, Interview $interview): bool
    {
        return $user->is($interview->user);
    }

    public function restore(User $user, Interview $interview): bool
    {
        return $user->is($interview->user);
    }

    public function forceDelete(User $user, Interview $interview): bool
    {
        return $user->is($interview->user);
    }
}
