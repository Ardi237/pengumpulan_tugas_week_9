<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::query()
            ->where('role', UserRole::MAHASISWA->value)
            ->firstOrFail();

        Course::query()->each(function (Course $course) use ($student): void {
            Enrollment::query()->firstOrCreate([
                'course_id' => $course->id,
                'student_id' => $student->id,
            ]);
        });
    }
}
