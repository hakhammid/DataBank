<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModuleEnrollment extends Model
{
    protected $table = 'module_enrollments';

    protected $fillable = [
        'user_id',
        'enrolled_by',
        'course_code',
    ];

    /**
     * Get the student who is enrolled.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the faculty who enrolled the student.
     */
    public function enrolledByFaculty(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }
}
