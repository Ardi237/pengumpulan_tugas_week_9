<?php

namespace Tests\Feature;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\StoreGradeRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class AcademicValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_requires_a_user_with_dosen_role_as_lecturer(): void
    {
        $admin = User::factory()->admin()->create();
        $student = User::factory()->mahasiswa()->create();

        $data = [
            'code' => 'WEB401',
            'name' => 'Pengujian Aplikasi Web',
            'description' => null,
            'lecturer_id' => $student->id,
        ];

        $request = StoreCourseRequest::create('/courses', 'POST', $data);
        $request->setUserResolver(fn () => $admin);

        $this->assertTrue($request->authorize());
        $this->assertTrue(Validator::make($data, $request->rules())->fails());

        $lecturer = User::factory()->dosen()->create();
        $data['lecturer_id'] = $lecturer->id;

        $this->assertFalse(Validator::make($data, $request->rules())->fails());
    }

    public function test_grade_requires_enrollment_and_score_between_zero_and_one_hundred(): void
    {
        $admin = User::factory()->admin()->create();
        $lecturer = User::factory()->dosen()->create();
        $student = User::factory()->mahasiswa()->create();

        $course = Course::query()->create([
            'code' => 'WEB402',
            'name' => 'Validasi Nilai',
            'lecturer_id' => $lecturer->id,
        ]);

        $data = [
            'student_id' => $student->id,
            'assessment_name' => 'UTS',
            'score' => 120,
            'notes' => null,
        ];

        $request = StoreGradeRequest::create('/courses/'.$course->id.'/grades', 'POST', $data);
        $this->attachRouteAndUser($request, $course, $admin);

        $validator = Validator::make($data, $request->rules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('student_id', $validator->errors()->toArray());
        $this->assertArrayHasKey('score', $validator->errors()->toArray());

        Enrollment::query()->create([
            'course_id' => $course->id,
            'student_id' => $student->id,
        ]);
        $data['score'] = 85;

        $request = StoreGradeRequest::create('/courses/'.$course->id.'/grades', 'POST', $data);
        $this->attachRouteAndUser($request, $course, $admin);

        $this->assertTrue($request->authorize());
        $this->assertFalse(Validator::make($data, $request->rules())->fails());
    }

    private function attachRouteAndUser(FormRequest $request, Course $course, User $user): void
    {
        $route = new Route(['POST'], '/courses/{course}/grades', fn () => null);
        $route->bind($request);
        $route->setParameter('course', $course);

        $request->setRouteResolver(fn () => $route);
        $request->setUserResolver(fn () => $user);
    }
}
