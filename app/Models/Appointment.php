<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'counselor_id',
        'session_topic',   // ✅ Make sure this is here
        'preferred_date',
        'preferred_time',
        'notes',
        'email',
        'status',];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function counselor()
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }
}
