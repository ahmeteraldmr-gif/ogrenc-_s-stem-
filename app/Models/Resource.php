<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Resource extends Model
{
    protected $fillable = [
        'name',
        'field_id',
        'course_id',
        'description',
        'created_by_user_id',
        'is_admin_resource',
    ];

    protected function casts(): array
    {
        return [
            'is_admin_resource' => 'boolean',
        ];
    }

    // İlişkiler
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function studentResources(): HasMany
    {
        return $this->hasMany(StudentResource::class);
    }

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Scope'lar
    public function scopeAdminResources(Builder $query): Builder
    {
        return $query->where('is_admin_resource', true);
    }

    public function scopeCoachResources(Builder $query, $userId): Builder
    {
        return $query->where('created_by_user_id', $userId)
            ->where('is_admin_resource', false);
    }

    // Helper metodlar
    public function getAssignedStudentsCountAttribute(): int
    {
        return $this->studentResources()->distinct('student_id')->count('student_id');
    }
}

