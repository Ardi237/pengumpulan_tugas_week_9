<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AcademicWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_users_courses_and_enrollments(): void
    {
        $admin = User::factory()->admin()->create();
        $lecturer = User::factory()->dosen()->create();
        $student = User::factory()->mahasiswa()->create();

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Mahasiswa Baru',
                'email' => 'mahasiswa.baru@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'role' => UserRole::MAHASISWA->value,
            ])
            ->assertRedirect(route('admin.users.index'));

        $createdUser = User::query()->where('email', 'mahasiswa.baru@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('password123', $createdUser->password));
        $this->assertTrue($createdUser->isMahasiswa());

        $this->actingAs($admin)
            ->post(route('admin.courses.store'), [
                'code' => 'web510',
                'name' => 'Integrasi Laravel',
                'description' => 'Pengujian alur akademik.',
                'lecturer_id' => $lecturer->id,
            ])
            ->assertRedirect();

        $course = Course::query()->where('code', 'WEB510')->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.courses.students.store', $course), [
                'student_id' => $student->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('enrollments', [
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);

        $this->actingAs($lecturer)->get(route('admin.users.index'))->assertForbidden();
    }

    public function test_last_admin_and_current_account_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertRedirect();

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_material_is_private_and_only_available_to_authorized_course_users(): void
    {
        Storage::fake('local');

        $lecturer = User::factory()->dosen()->create();
        $student = User::factory()->mahasiswa()->create();
        $outsider = User::factory()->mahasiswa()->create();
        $course = $this->createCourseFor($lecturer, $student);

        $this->actingAs($lecturer)
            ->post(route('courses.materials.store', $course), [
                'title' => 'Keamanan Middleware',
                'description' => 'Materi private.',
                'file' => UploadedFile::fake()->create('middleware.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect();

        $material = Material::query()->where('course_id', $course->id)->firstOrFail();
        Storage::disk('local')->assertExists($material->file_path);

        $this->actingAs($student)->get(route('materials.show', $material))->assertOk();
        $this->actingAs($student)->get(route('materials.download', $material))->assertOk();
        $this->actingAs($outsider)->get(route('materials.show', $material))->assertForbidden();
        $this->actingAs($outsider)->get(route('materials.download', $material))->assertForbidden();
    }

    public function test_lecturer_can_grade_enrolled_student_and_student_only_sees_own_grade(): void
    {
        $lecturer = User::factory()->dosen()->create();
        $student = User::factory()->mahasiswa()->create();
        $outsider = User::factory()->mahasiswa()->create();
        $course = $this->createCourseFor($lecturer, $student);

        $this->actingAs($lecturer)
            ->post(route('courses.grades.store', $course), [
                'student_id' => $student->id,
                'assessment_name' => 'Praktikum Middleware',
                'score' => 92,
                'notes' => 'Akses sudah tepat.',
            ])
            ->assertRedirect();

        $grade = Grade::query()->where('student_id', $student->id)->firstOrFail();

        $this->actingAs($student)->get(route('student.grades.index'))->assertOk();
        $this->actingAs($student)->get(route('student.grades.show', $grade))->assertOk();
        $this->actingAs($outsider)->get(route('student.grades.show', $grade))->assertForbidden();

        $this->actingAs($lecturer)
            ->post(route('courses.grades.store', $course), [
                'student_id' => $student->id,
                'assessment_name' => 'Nilai Tidak Valid',
                'score' => 101,
            ])
            ->assertSessionHasErrors('score');
    }

    public function test_each_role_can_render_its_dashboard(): void
    {
        foreach ([
            User::factory()->admin()->create(),
            User::factory()->dosen()->create(),
            User::factory()->mahasiswa()->create(),
        ] as $user) {
            $this->actingAs($user)->get(route('dashboard'))->assertOk();
        }
    }

    public function test_authorized_users_can_render_all_primary_management_pages(): void
    {
        $admin = User::factory()->admin()->create();
        $lecturer = User::factory()->dosen()->create();
        $student = User::factory()->mahasiswa()->create();
        $course = $this->createCourseFor($lecturer, $student);

        $material = Material::query()->create([
            'course_id' => $course->id,
            'title' => 'Materi Pengujian',
            'description' => 'Memastikan halaman materi dapat dirender.',
            'file_path' => 'materials/testing.pdf',
            'uploaded_by' => $lecturer->id,
        ]);

        $grade = Grade::query()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
            'assessment_name' => 'Pengujian Halaman',
            'score' => 88,
            'created_by' => $lecturer->id,
        ]);

        $adminPages = [
            route('admin.users.index'),
            route('admin.users.create'),
            route('admin.users.edit', $student),
            route('admin.courses.index'),
            route('admin.courses.create'),
            route('admin.courses.show', $course),
            route('admin.courses.edit', $course),
            route('admin.courses.students.index', $course),
        ];

        foreach ($adminPages as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }

        $lecturerPages = [
            route('courses.index'),
            route('courses.show', $course),
            route('courses.materials.index', $course),
            route('courses.materials.create', $course),
            route('materials.show', $material),
            route('materials.edit', $material),
            route('courses.grades.index', $course),
            route('courses.grades.create', $course),
            route('grades.edit', $grade),
        ];

        foreach ($lecturerPages as $url) {
            $this->actingAs($lecturer)->get($url)->assertOk();
        }

        $studentPages = [
            route('courses.index'),
            route('courses.show', $course),
            route('courses.materials.index', $course),
            route('materials.show', $material),
            route('student.grades.index'),
            route('student.grades.show', $grade),
        ];

        foreach ($studentPages as $url) {
            $this->actingAs($student)->get($url)->assertOk();
        }
    }

    private function createCourseFor(User $lecturer, User $student): Course
    {
        $course = Course::query()->create([
            'code' => 'WEB'.fake()->unique()->numberBetween(100, 999),
            'name' => 'Pemrograman Web',
            'description' => 'Kelas pengujian.',
            'lecturer_id' => $lecturer->id,
        ]);

        Enrollment::query()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);

        return $course;
    }
}
