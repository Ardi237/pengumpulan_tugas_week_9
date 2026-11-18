<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class StudentGradeController extends Controller
{
    public function index(Request $request): View
    {
        $student = $request->user();

        abort_unless($student->isMahasiswa(), 403);

        $grades = Grade::query()
            ->where('student_id', $student->getKey())
            ->with(['course.lecturer', 'creator'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('student.grades.index', compact('grades'));
    }

    public function show(Request $request, Grade $grade): View
    {
        abort_unless($request->user()->isMahasiswa(), 403);
        Gate::authorize('view', $grade);

        $grade->load(['course.lecturer', 'creator']);

        return view('student.grades.show', compact('grade'));
    }
}
