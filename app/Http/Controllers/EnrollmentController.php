<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(Course $course): View
    {
        Gate::authorize('update', $course);

        $course->load([
            'lecturer',
            'students' => fn ($students) => $students->orderBy('name'),
        ]);

        $availableStudents = User::query()
            ->role(UserRole::MAHASISWA)
            ->whereDoesntHave('courses', fn ($courses) => $courses->whereKey($course->id))
            ->orderBy('name')
            ->get();

        return view('admin.courses.students', compact('course', 'availableStudents'));
    }

    public function store(Request $request, Course $course): RedirectResponse
    {
        Gate::authorize('update', $course);

        $validated = $request->validate([
            'student_id' => [
                'required',
                'integer',
                Rule::exists(User::class, 'id')
                    ->where(fn ($query) => $query->where('role', UserRole::MAHASISWA->value)),
                Rule::unique(Enrollment::class, 'student_id')
                    ->where(fn ($query) => $query->where('course_id', $course->id)),
            ],
        ]);

        $course->enrollments()->create($validated);

        return back()->with('success', 'Mahasiswa berhasil ditambahkan ke mata kuliah.');
    }

    public function destroy(Course $course, User $student): RedirectResponse
    {
        Gate::authorize('update', $course);

        $deleted = Enrollment::query()
            ->where('course_id', $course->id)
            ->where('student_id', $student->id)
            ->delete();

        abort_if($deleted === 0, 404);

        return back()->with('success', 'Mahasiswa berhasil dikeluarkan dari mata kuliah.');
    }
}
