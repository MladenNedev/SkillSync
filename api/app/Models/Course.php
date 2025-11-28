<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'image_url',
        'description',
        'estimated_hours',
        'difficulty'
    ];

    public function userProgress()
    {
        return $this->hasMany(UserCourseProgress::class);
    }

    public function studySessions()
    {
        return $this->hasMany(StudySession::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_course_progress')
                    ->withPivot(['status','progress_percent','last_accessed_at','total_minutes_spent','completed_at']);
    }
}
