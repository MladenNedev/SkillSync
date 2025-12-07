<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserChallengeProgress extends Model
{
    use HasFactory;

    protected $table = 'user_challenge_progress';

    protected $fillable = [
        'user_id',
        'challenge_id',
        'status',
        'progress_percent',
        'last_accessed_at',
        'total_minutes_spent',
        'completed_at',
    ];

    protected $casts = [
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_minutes_spent' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
