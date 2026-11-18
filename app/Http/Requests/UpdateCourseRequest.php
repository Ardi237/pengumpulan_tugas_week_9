<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $course = $this->route('course');

        return $course instanceof Course
            && ($this->user()?->can('update', $course) ?? false);
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('code')) {
            $this->merge(['code' => strtoupper(trim((string) $this->input('code')))]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $course = $this->route('course');
        $courseId = $course instanceof Course ? $course->getKey() : $course;

        return [
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique(Course::class, 'code')->ignore($courseId),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'lecturer_id' => [
                'required',
                'integer',
                Rule::exists(User::class, 'id')
                    ->where(fn ($query) => $query->where('role', UserRole::DOSEN->value)),
            ],
        ];
    }
}
