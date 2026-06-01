<?php
namespace App\Models;

use App\Models\User;
use App\Models\Module;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'courses';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'course_name',
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    /**
     * Get the department that owns this course.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user that owns the module.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function allCourses()
    {
        return self::with('department')->latest()->paginate(10);
    }

    /**
     * Get the modules that target this course (degree program).
     * Many-to-many via module_courses pivot table.
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'module_courses')
                    ->withTimestamps();
    }
}
