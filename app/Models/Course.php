<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'lecturer_id',
    ];

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id')
            ->withTimestamps();
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function scopeTaughtBy(Builder $query, User|int $lecturer): Builder
    {
        $lecturerId = $lecturer instanceof User ? $lecturer->getKey() : $lecturer;

        return $query->where('lecturer_id', $lecturerId);
    }

    public function scopeEnrolledBy(Builder $query, User|int $student): Builder
    {
        $studentId = $student instanceof User ? $student->getKey() : $student;

        return $query->whereHas('enrollments', fn (Builder $enrollments) => $enrollments
            ->where('student_id', $studentId));
    }

    public function isTaughtBy(User $user): bool
    {
        return (int) $this->lecturer_id === (int) $user->getKey();
    }

    public function hasStudent(User $user): bool
    {
        if ($this->relationLoaded('students')) {
            return $this->students->contains($user);
        }

        return $this->students()->whereKey($user->getKey())->exists();
    }
}
