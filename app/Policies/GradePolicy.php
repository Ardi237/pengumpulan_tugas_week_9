<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\Grade;
use App\Models\User;

class GradePolicy
{
    /**
     * Administrators may perform every grade action.
     */
    public function before(User $user, string $ability): ?bool
    {
        return $user->isAdmin() ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isDosen() || $user->isMahasiswa();
    }

    public function view(User $user, Grade $grade): bool
    {
        if ($user->isMahasiswa()) {
            return (int) $grade->student_id === (int) $user->getKey();
        }

        return $this->lecturerOwnsGrade($user, $grade);
    }

    public function create(User $user, Course $course): bool
    {
        return $user->isDosen() && $course->isTaughtBy($user);
    }

    public function update(User $user, Grade $grade): bool
    {
        return $this->lecturerOwnsGrade($user, $grade);
    }

    public function delete(User $user, Grade $grade): bool
    {
        return $this->lecturerOwnsGrade($user, $grade);
    }

    private function lecturerOwnsGrade(User $user, Grade $grade): bool
    {
        return $user->isDosen()
            && $grade->course !== null
            && $grade->course->isTaughtBy($user);
    }
}
