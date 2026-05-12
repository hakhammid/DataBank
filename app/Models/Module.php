<?php

namespace App\Models;
use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\ModuleDownload;
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
        'course_id',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function moduleDownloads()
    {
        return $this->hasMany(ModuleDownload::class);
    }
}
