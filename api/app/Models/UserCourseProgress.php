<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserCourseProgress extends Model
{
    use HasFactory;

    protected $table = 'user_course_progress';

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'progress_percent',
        'last_accessed_at',
        'total_minutes_spent',
        'completed_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
