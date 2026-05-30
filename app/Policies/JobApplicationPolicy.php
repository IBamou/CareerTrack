<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;

class JobApplicationPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JobApplication $jobApplication): bool
    {
        return $user->is($jobApplication->appliedBy);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobApplication $jobApplication): bool
    {
        return $user->is($jobApplication->appliedBy);
    }

    public function archive(User $user, JobApplication $jobApplication): bool
    {
        return $user->is($jobApplication->appliedBy);
    }

    public function delete(User $user, JobApplication $jobApplication): bool
    {
        return $user->is($jobApplication->appliedBy);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JobApplication $jobApplication): bool
    {
        return $user->is($jobApplication->appliedBy);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JobApplication $jobApplication): bool
    {
        return $user->is($jobApplication->appliedBy);
    }
}
