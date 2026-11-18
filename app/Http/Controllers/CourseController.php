<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Course::class);

        $user = $request->user();
        $query = Course::query()
            ->with('lecturer')
            ->withCount(['students', 'materials']);

        if ($user->isDosen()) {
            $query->taughtBy($user);
        } elseif ($user->isMahasiswa()) {
            $query->enrolledBy($user);
        } elseif (! $user->isAdmin()) {
            abort(403, 'Role pengguna tidak dikenali.');
        }

        if ($search = trim((string) $request->query('search'))) {
            $query->where(function ($courses) use ($search): void {
                $courses
                    ->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        /** @var LengthAwarePaginator $courses */
        $courses = $query->orderBy('code')->paginate(10)->withQueryString();

        return view('courses.index', compact('courses'));
    }

    public function show(Request $request, Course $course): View
    {
        Gate::authorize('view', $course);

        $course->load('lecturer')->loadCount(['students', 'materials', 'grades']);
        $materials = $course->materials()->with('uploader')->latest()->get();
        $grades = collect();

        if ($request->user()->isMahasiswa()) {
            $grades = $course->grades()
                ->where('student_id', $request->user()->id)
                ->latest()
                ->get();
        }

        return view('courses.show', compact('course', 'materials', 'grades'));
    }
}
