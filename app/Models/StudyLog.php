<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudyLog extends Model
{
    protected $fillable = [
        'student_id',
        'topic_id',
        'sub_topic_id',
        'resource_type',
        'resource_title',
        'description',
        'study_date',
    ];

    protected function casts(): array
    {
        return [
            'study_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
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
