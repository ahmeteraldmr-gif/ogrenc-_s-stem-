<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'order',
        'is_active',
        'category_type',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class)->orderBy('order');
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    // Scopes
    public function scopeCourseFields($query)
    {
        return $query->whereIn('category_type', ['course_field', 'both']);
    }

    public function scopeExamCategories($query)
    {
        return $query->whereIn('category_type', ['exam_category', 'both']);
    }
}
