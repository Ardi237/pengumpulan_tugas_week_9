<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course');

        return $course instanceof Course
            && ($this->user()?->can('create', [Grade::class, $course]) ?? false);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $courseId = $this->courseId();
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
                        ->where('student_id', $studentId)),
            ],
            'score' => ['required', 'numeric', 'between:0,100'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    private function courseId(): ?int
    {
        $course = $this->route('course');

        if ($course instanceof Course) {
            return (int) $course->getKey();
        }

        return is_numeric($course) ? (int) $course : null;
    }
}
