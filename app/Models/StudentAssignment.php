<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StudentAssignment extends Model
{
    protected $fillable = [
        'student_id',
        'coach_id',
        'course_id',
        'topic_id',
        'sub_topic_id',
        'assignment_type',
        'template_id',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
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

    public function template(): BelongsTo
    {
        return $this->belongsTo(CourseTemplate::class, 'template_id');
    }

    public function progress(): HasOne
    {
        return $this->hasOne(AssignmentProgress::class, 'student_assignment_id');
    }
}
