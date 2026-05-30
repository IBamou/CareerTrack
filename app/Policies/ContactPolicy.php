<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;

class ContactPolicy
{
    public function view(User $user, Contact $contact): bool
    {
        return $user->is($contact->user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Contact $contact): bool
    {
        return $user->is($contact->user);
    }

    public function delete(User $user, Contact $contact): bool
    {
        return $user->is($contact->user);
    }

    public function restore(User $user, Contact $contact): bool
    {
        return $user->is($contact->user);
    }

    public function forceDelete(User $user, Contact $contact): bool
    {
        return $user->is($contact->user);
    }
}
