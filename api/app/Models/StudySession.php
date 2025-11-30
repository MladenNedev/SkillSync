<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudySession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'challenge_id',
        'type',
        'started_at',
        'ended_at',
        'duration_minutes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
