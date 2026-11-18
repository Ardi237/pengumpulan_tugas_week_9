<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGradeRequest;
use App\Http\Requests\UpdateGradeRequest;
use App\Models\Course;
use App\Models\Grade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Course $course): View
    {
        Gate::authorize('create', [Grade::class, $course]);

        $grades = $course->grades()
            ->with(['student', 'creator'])
            ->orderBy('assessment_name')
            ->orderBy('student_id')
            ->paginate(15);

        return view('courses.grades.index', compact('course', 'grades'));
    }

    public function create(Course $course): View
    {
        Gate::authorize('create', [Grade::class, $course]);

        $students = $course->students()
            ->orderBy('name')
            ->get();

        return view('courses.grades.create', compact('course', 'students'));
    }

    public function store(StoreGradeRequest $request, Course $course): RedirectResponse
    {
        $validated = $request->validated();

        $course->grades()->create([
            'student_id' => $validated['student_id'],
            'assessment_name' => $validated['assessment_name'],
            'score' => $validated['score'],
            'notes' => $validated['notes'] ?? null,
            'created_by' => $request->user()->getKey(),
        ]);

        return redirect()
            ->route('courses.grades.index', $course)
            ->with('success', 'Nilai berhasil ditambahkan.');
    }

    public function edit(Grade $grade): View
    {
        Gate::authorize('update', $grade);

        $grade->load('course');
        $students = $grade->course->students()
            ->orderBy('name')
            ->get();

        return view('grades.edit', compact('grade', 'students'));
    }

    public function update(UpdateGradeRequest $request, Grade $grade): RedirectResponse
    {
        $validated = $request->validated();

        $grade->update([
            'student_id' => $validated['student_id'],
            'assessment_name' => $validated['assessment_name'],
            'score' => $validated['score'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('courses.grades.index', $grade->course_id)
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        Gate::authorize('delete', $grade);

        $courseId = $grade->course_id;
        $grade->delete();

        return redirect()
            ->route('courses.grades.index', $courseId)
            ->with('success', 'Nilai berhasil dihapus.');
    }
}
