<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $grade = $this->route('grade');

        return $grade instanceof Grade
            && ($this->user()?->can('update', $grade) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $grade = $this->route('grade');
        $courseId = $this->courseId($grade);
        $studentId = $this->integer('student_id');

        return [
            'student_id' => [
                'required',
                'integer',
                Rule::exists(User::class, 'id')
                    ->where(fn ($query) => $query->where('role', UserRole::MAHASISWA->value)),
                Rule::exists('enrollments', 'student_id')
                    ->where(fn ($query) => $query->where('course_id', $courseId)),
            ],
            'assessment_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Grade::class, 'assessment_name')
                    ->where(fn ($query) => $query
                        ->where('course_id', $courseId)
                        ->where('student_id', $studentId))
                    ->ignore($grade instanceof Grade ? $grade->getKey() : $grade),
            ],
            'score' => ['required', 'numeric', 'between:0,100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    private function courseId(mixed $grade): ?int
    {
        $course = $this->route('course');

        if ($course instanceof Course) {
            return (int) $course->getKey();
        }

        if (is_numeric($course)) {
            return (int) $course;
        }

        return $grade instanceof Grade ? (int) $grade->course_id : null;
    }
}
