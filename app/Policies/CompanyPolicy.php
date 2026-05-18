<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    public function view(User $user, Company $company): bool
    {
        return $user->is($company->user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Company $company): bool
    {
        return $user->is($company->user);
    }

    public function delete(User $user, Company $company): bool
    {
        return $user->is($company->user);
    }

    public function archive(User $user, Company $company): bool
    {
        return $user->is($company->user);
    }

    public function restore(User $user, Company $company): bool
    {
        return $user->is($company->user);
    }

    public function forceDelete(User $user, Company $company): bool
    {
        return $user->is($company->user);
    }
}
