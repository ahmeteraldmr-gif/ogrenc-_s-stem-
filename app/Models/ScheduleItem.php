<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScheduleItem extends Model
{
    protected $fillable = [
        'schedule_id',
        'day_of_week',
        'time_slot',
        'course_id',
        'topic_id',
        'sub_topic_id',
        'student_resource_id',
        'question_count',
        'description',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'question_count' => 'integer',
            'order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(StudySchedule::class, 'schedule_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    public function subTopic(): BelongsTo
    {
        return $this->belongsTo(SubTopic::class);
    }

    public function studentResource(): BelongsTo
    {
        return $this->belongsTo(StudentResource::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(ScheduleProgress::class, 'schedule_item_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDayNameAttribute(): string
    {
        $days = [
            1 => 'Pazartesi',
            2 => 'Salı',
            3 => 'Çarşamba',
            4 => 'Perşembe',
            5 => 'Cuma',
            6 => 'Cumartesi',
            7 => 'Pazar',
        ];

        return $days[$this->day_of_week] ?? '';
    }
}
