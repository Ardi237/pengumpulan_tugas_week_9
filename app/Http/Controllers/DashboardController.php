<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Material;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            $stats = [
                'users' => User::query()->count(),
                'lecturers' => User::query()->role(UserRole::DOSEN)->count(),
                'students' => User::query()->role(UserRole::MAHASISWA)->count(),
                'courses' => Course::query()->count(),
                'materials' => Material::query()->count(),
                'grades' => Grade::query()->count(),
            ];

            $courses = Course::query()
                ->with('lecturer')
                ->withCount(['students', 'materials', 'grades'])
                ->latest()
                ->limit(5)
                ->get();
        } elseif ($user->isDosen()) {
            $courseIds = Course::query()->taughtBy($user)->select('id');

            $stats = [
                'courses' => Course::query()->taughtBy($user)->count(),
                'students' => Enrollment::query()->whereIn('course_id', clone $courseIds)->distinct('student_id')->count('student_id'),
                'materials' => Material::query()->whereIn('course_id', clone $courseIds)->count(),
                'grades' => Grade::query()->whereIn('course_id', clone $courseIds)->count(),
            ];

            $courses = Course::query()
                ->taughtBy($user)
                ->with('lecturer')
                ->withCount(['students', 'materials', 'grades'])
                ->latest()
                ->limit(5)
                ->get();
        } elseif ($user->isMahasiswa()) {
            $courseIds = Enrollment::query()->where('student_id', $user->id)->select('course_id');

            $stats = [
                'courses' => Enrollment::query()->where('student_id', $user->id)->count(),
                'materials' => Material::query()->whereIn('course_id', clone $courseIds)->count(),
                'grades' => Grade::query()->where('student_id', $user->id)->count(),
            ];

            $courses = Course::query()
                ->enrolledBy($user)
                ->with('lecturer')
                ->withCount('materials')
                ->latest()
                ->limit(5)
                ->get();
        } else {
            abort(403, 'Role pengguna tidak dikenali.');
        }

        return view('dashboard', compact('user', 'stats', 'courses'));
    }
}
