<?php

namespace App\Models;

use App\Models\Course;
use App\Models\Module;
use App\Models\Department;
use App\Models\ModuleDownload;
use App\Models\ModuleEnrollment;
use App\Models\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_number',
        'first_name',
        'middle_initial',
        'last_name',
        'email',
        'password',
        'usertype',
        'profile_picture',
        'department_id',
        'course_id'
    ];

    /**
     * Get the user's full name.
     */
    public function getNameAttribute()
    {
        if (empty($this->first_name) && empty($this->last_name)) {
            return '';
        }
        
        $middle = !empty($this->middle_initial) ? $this->middle_initial . '. ' : '';
        return "{$this->first_name} {$middle}{$this->last_name}";
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the modules for the user.
     */
    public function modules(): HasMany
    {
        return $this->hasMany(Module::class, 'user_id');
    }

    public function facultyModule(): HasMany {
        return $this->hasMany(Module::class, 'user_id');
    }


    public function allFaculty() {
        return self::where('usertype', 'faculty')->latest()->paginate(10);
    }

    public function allStudent() {
        return self::where('usertype', 'student')->latest()->paginate(10);
    }

    public function allAdmin() {
        return self::where('usertype', 'admin')->latest()->paginate(10);
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

    /**
     * Get the course codes this student has been enrolled in by faculty.
     */
    public function enrolledCourseCodes(): HasMany
    {
        return $this->hasMany(ModuleEnrollment::class, 'user_id');
    }

    /**
     * Get the enrollments this faculty member has created.
     */
    public function createdEnrollments(): HasMany
    {
        return $this->hasMany(ModuleEnrollment::class, 'enrolled_by');
    }

    /**
     * Get all notifications for this user.
     */
    public function moduleNotifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    /**
     * Get unread notifications count.
     */
    public function unreadNotificationsCount(): int
    {
        return $this->moduleNotifications()->whereNull('read_at')->count();
    }
}
