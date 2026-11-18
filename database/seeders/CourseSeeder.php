<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer = User::query()
            ->where('role', UserRole::DOSEN->value)
            ->firstOrFail();

        $courses = [
            [
                'code' => 'WEB201',
                'name' => 'Pemrograman Web',
                'description' => 'Penerapan Laravel, middleware, autentikasi, dan otorisasi aplikasi web.',
            ],
            [
                'code' => 'DBS201',
                'name' => 'Basis Data',
                'description' => 'Perancangan dan pengelolaan basis data untuk aplikasi akademik.',
            ],
        ];

        foreach ($courses as $course) {
            Course::query()->updateOrCreate(
                ['code' => $course['code']],
                [
                    'name' => $course['name'],
                    'description' => $course['description'],
                    'lecturer_id' => $lecturer->id,
                ],
            );
        }
    }
}
