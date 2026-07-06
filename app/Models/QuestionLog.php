<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionLog extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'topic_id',
        'sub_topic_id',
        'total_questions',
        'correct_answers',
        'wrong_answers',
        'blank_answers',
        'log_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'log_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
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
}
