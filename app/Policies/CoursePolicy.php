<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Administrators may perform every course action.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isDosen() || $user->isMahasiswa();
    }

    public function view(User $user, Course $course): bool
    {
        if ($user->isDosen()) {
            return $course->isTaughtBy($user);
        }

        return $user->isMahasiswa() && $course->hasStudent($user);
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Course $course): bool
    {
        return false;
    }

    public function delete(User $user, Course $course): bool
    {
        return false;
    }
}
