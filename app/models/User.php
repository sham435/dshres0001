<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'username',
        'phone',
        'address',
        'dob',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class, 'student_id');
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'class_student', 'student_id', 'class_id')->withPivot('academic_year');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'student_id');
    }

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class, 'posted_by');
    }

    public function timetables(): HasMany
    {
        return $this->hasMany(Timetable::class, 'teacher_id');
    }

    public function studyMaterials(): HasMany
    {
        return $this->hasMany(StudyMaterial::class, 'teacher_id');
    }

    public function teacherClasses(): HasMany
    {
        return $this->hasMany(TeacherClass::class, 'teacher_id');
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'student_parent', 'parent_id', 'student_id');
    }

    public function hasRole($roleName): bool
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isLibrarian(): bool
    {
        return $this->hasRole('librarian');
    }

    public function isAccountant(): bool
    {
        return $this->hasRole('accountant');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    public function isParent(): bool
    {
        return $this->hasRole('parent');
    }
}
