<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
