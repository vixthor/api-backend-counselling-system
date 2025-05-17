<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'is_anonymous'];
    protected $casts = [
        'is_anonymous' => 'boolean',
    ];
    protected $attributes = [
        'is_anonymous' => false,
    ];
    protected $hidden = ['remember_token'];

    /**
     * Define the relationship with the User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'participant1_id');
    }

    public function getDisplayNameAttribute()
    {
        return $this->is_anonymous ? 'Anonymous Student' : $this->user->name;
    }
}
