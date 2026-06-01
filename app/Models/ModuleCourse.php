<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleCourse extends Model
{
    protected $table = 'module_courses';

    protected $fillable = [
        'module_id',
        'course_id',
    ];

    /**
     * Get the module for this pivot record.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the course (degree program) for this pivot record.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
