<?php

use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentGradeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    Route::get('/courses/{course}/materials', [MaterialController::class, 'index'])
        ->name('courses.materials.index');
    Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');
    Route::get('/materials/{material}/download', [MaterialController::class, 'download'])
        ->name('materials.download');

    Route::middleware('role:admin,dosen')->group(function () {
        Route::get('/courses/{course}/materials/create', [MaterialController::class, 'create'])
            ->name('courses.materials.create');
        Route::post('/courses/{course}/materials', [MaterialController::class, 'store'])
            ->name('courses.materials.store');
        Route::get('/materials/{material}/edit', [MaterialController::class, 'edit'])
            ->name('materials.edit');
        Route::match(['put', 'patch'], '/materials/{material}', [MaterialController::class, 'update'])
            ->name('materials.update');
        Route::delete('/materials/{material}', [MaterialController::class, 'destroy'])
            ->name('materials.destroy');

        Route::get('/courses/{course}/grades', [GradeController::class, 'index'])
            ->name('courses.grades.index');
        Route::get('/courses/{course}/grades/create', [GradeController::class, 'create'])
            ->name('courses.grades.create');
        Route::post('/courses/{course}/grades', [GradeController::class, 'store'])
            ->name('courses.grades.store');
        Route::get('/grades/{grade}/edit', [GradeController::class, 'edit'])->name('grades.edit');
        Route::match(['put', 'patch'], '/grades/{grade}', [GradeController::class, 'update'])
            ->name('grades.update');
        Route::delete('/grades/{grade}', [GradeController::class, 'destroy'])->name('grades.destroy');
    });

    Route::middleware('role:mahasiswa')->group(function () {
        Route::get('/my-grades', [StudentGradeController::class, 'index'])
            ->name('student.grades.index');
        Route::get('/my-grades/{grade}', [StudentGradeController::class, 'show'])
            ->name('student.grades.show');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function () {
        Route::resource('users', AdminUserController::class)->except('show');
        Route::resource('courses', AdminCourseController::class);

        Route::get('/courses/{course}/students', [EnrollmentController::class, 'index'])
            ->name('courses.students.index');
        Route::post('/courses/{course}/students', [EnrollmentController::class, 'store'])
            ->name('courses.students.store');
        Route::delete('/courses/{course}/students/{student}', [EnrollmentController::class, 'destroy'])
            ->name('courses.students.destroy');
    });
});

require __DIR__.'/auth.php';
