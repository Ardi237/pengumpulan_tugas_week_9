<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Course::class);

        $query = Course::query()
            ->with('lecturer')
            ->withCount(['students', 'materials', 'grades']);

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($courses) use ($search): void {
                $courses
                    ->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhereHas('lecturer', fn ($lecturers) => $lecturers
                        ->where('name', 'like', "%{$search}%"));
            });
        }

        $courses = $query->orderBy('code')->paginate(15)->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function create(): View
    {
        Gate::authorize('create', Course::class);

        $lecturers = $this->lecturers();

        return view('admin.courses.create', compact('lecturers'));
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $course = Course::query()->create($request->validated());

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Mata kuliah berhasil dibuat.');
    }

    public function show(Course $course): View
    {
        Gate::authorize('view', $course);

        $course->load([
            'lecturer',
            'students' => fn ($students) => $students->orderBy('name'),
            'materials' => fn ($materials) => $materials->latest(),
            'grades.student',
        ])->loadCount(['students', 'materials', 'grades']);

        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course): View
    {
        Gate::authorize('update', $course);

        $lecturers = $this->lecturers();

        return view('admin.courses.edit', compact('course', 'lecturers'));
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $course->update($request->validated());

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(Course $course): RedirectResponse
    {
        Gate::authorize('delete', $course);

        $materialPaths = $course->materials()
            ->whereNotNull('file_path')
            ->pluck('file_path')
            ->all();

        $course->delete();

        if ($materialPaths !== []) {
            Storage::disk('local')->delete($materialPaths);
        }

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Mata kuliah berhasil dihapus.');
    }

    private function lecturers()
    {
        return User::query()
            ->role(UserRole::DOSEN)
            ->orderBy('name')
            ->get();
    }
}
