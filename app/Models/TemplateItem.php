<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateItem extends Model
{
    protected $fillable = [
        'template_id',
        'course_id',
        'topic_id',
        'sub_topic_id',
        'order',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(CourseTemplate::class, 'template_id');
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
