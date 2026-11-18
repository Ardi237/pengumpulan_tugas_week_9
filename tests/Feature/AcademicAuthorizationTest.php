<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AcademicAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_role_middleware_restricts_routes_and_supports_multiple_roles(): void
    {
        Route::middleware(['web', 'auth', 'role:admin'])
            ->get('/_test/admin', fn () => response()->noContent());

        Route::middleware(['web', 'auth', 'role:admin,dosen'])
            ->get('/_test/staff', fn () => response()->noContent());

        $admin = User::factory()->admin()->create();
        $lecturer = User::factory()->dosen()->create();
        $student = User::factory()->mahasiswa()->create();

        $this->actingAs($admin)->get('/_test/admin')->assertNoContent();
        $this->actingAs($lecturer)->get('/_test/admin')->assertForbidden();
        $this->actingAs($lecturer)->get('/_test/staff')->assertNoContent();
        $this->actingAs($student)->get('/_test/staff')->assertForbidden();
    }

    public function test_policies_enforce_course_ownership_enrollment_and_private_grades(): void
    {
        $admin = User::factory()->admin()->create();
        $lecturer = User::factory()->dosen()->create();
        $otherLecturer = User::factory()->dosen()->create();
        $student = User::factory()->mahasiswa()->create();
        $otherStudent = User::factory()->mahasiswa()->create();

        $course = Course::query()->create([
            'code' => 'WEB301',
            'name' => 'Keamanan Aplikasi Web',
            'description' => 'Pengujian kebijakan akses.',
            'lecturer_id' => $lecturer->id,
        ]);

        Enrollment::query()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);

        $material = Material::query()->create([
            'course_id' => $course->id,
            'title' => 'Middleware dan Policy',
            'file_path' => 'materials/middleware.pdf',
            'uploaded_by' => $lecturer->id,
        ]);

        $grade = Grade::query()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'assessment_name' => 'Praktikum Middleware',
            'score' => 90,
            'created_by' => $lecturer->id,
        ]);

        $this->assertTrue($admin->can('update', $course));
        $this->assertTrue($lecturer->can('view', $course));
        $this->assertFalse($otherLecturer->can('view', $course));
        $this->assertTrue($student->can('view', $course));
        $this->assertFalse($otherStudent->can('view', $course));

        $this->assertTrue($lecturer->can('create', [Material::class, $course]));
        $this->assertTrue($student->can('view', $material));
        $this->assertFalse($student->can('update', $material));

        $this->assertTrue($lecturer->can('create', [Grade::class, $course]));
        $this->assertTrue($student->can('view', $grade));
        $this->assertFalse($otherStudent->can('view', $grade));
    }
}
