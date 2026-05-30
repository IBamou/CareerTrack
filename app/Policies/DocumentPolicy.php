<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function view(User $user, Document $document): bool
    {
        return $user->is($document->user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Document $document): bool
    {
        return $user->is($document->user);
    }
}
