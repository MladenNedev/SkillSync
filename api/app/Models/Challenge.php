<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'difficulty',
        'estimated_minutes',
    ];

    public function userProgress()
    {
        return $this->hasMany(UserChallengeProgress::class);
    }

    public function studySessions()
    {
        return $this->hasMany(StudySession::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_challenge_progress')
            ->withPivot(['status', 'progress_percent', 'last_accessed_at', 'total_minutes_spent', 'completed_at']);
    }
}
