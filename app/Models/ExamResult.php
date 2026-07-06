<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    protected $fillable = [
        'student_id',
        'exam_name',
        'exam_type',
        'course_id',
        'field_id',
        'correct_answers',
        'wrong_answers',
        'blank_answers',
        'net_score',
        'exam_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'net_score' => 'decimal:2',
            'exam_date' => 'date',
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

    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class);
    }
}
