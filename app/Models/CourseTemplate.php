<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseTemplate extends Model
{
    protected $fillable = [
        'coach_id',
        'name',
        'description',
    ];

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(TemplateItem::class, 'template_id')->orderBy('order');
    }
}
