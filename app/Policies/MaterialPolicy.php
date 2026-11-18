<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Material;
use App\Models\User;

class MaterialPolicy
{
    /**
     * Administrators may perform every material action.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isDosen() || $user->isMahasiswa();
    }

    public function view(User $user, Material $material): bool
    {
        $course = $material->course;

        if ($course === null) {
            return false;
        }

        if ($user->isDosen()) {
            return $course->isTaughtBy($user);
        }

        return $user->isMahasiswa() && $course->hasStudent($user);
    }

    public function create(User $user, Course $course): bool
    {
        return $user->isDosen() && $course->isTaughtBy($user);
    }

    public function update(User $user, Material $material): bool
    {
        return $this->lecturerOwnsMaterial($user, $material);
    }

    public function delete(User $user, Material $material): bool
    {
        return $this->lecturerOwnsMaterial($user, $material);
    }

    private function lecturerOwnsMaterial(User $user, Material $material): bool
    {
        return $user->isDosen()
            && $material->course !== null
            && $material->course->isTaughtBy($user);
    }
}
