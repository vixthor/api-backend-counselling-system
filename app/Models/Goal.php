<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'type',
        'timeframe',
        'preferred_time',
        'completed',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relationship with GoalProgress
  
  
}