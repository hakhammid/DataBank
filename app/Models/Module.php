<?php

namespace App\Models;
use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\ModuleDownload;
use App\Models\ModuleEnrollment;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'modules';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'course_code',
        'title',
        'file',
        'isMajor',
        'semester',
        'user_id',
        'department_id',
        'status',
        'created_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    /**
     * Get the user that owns the module.
     */
    public function allModules()
    {
        return self::latest()->get();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the courses (degree programs) this module targets.
     * Many-to-many via module_courses pivot table.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'module_courses')
                    ->withTimestamps();
    }

    public function moduleDownloads()
    {
        return $this->hasMany(ModuleDownload::class);
    }

    /**
     * Get all enrollments for this module's course code.
     */
    public function enrollments()
    {
        return ModuleEnrollment::where('course_code', $this->course_code)->get();
    }
}
