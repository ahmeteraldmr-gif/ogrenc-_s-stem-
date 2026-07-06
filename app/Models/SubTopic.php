<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubTopic extends Model
{
    protected $fillable = [
        'topic_id',
        'name',
        'order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
        'is_active' => 'boolean',
        ];
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }
}
