<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Add role to mass assignable attributes
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user is a student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if the user is a counselor.
     */
    public function isCounselor(): bool
    {
        return $this->role === 'counselor';
    }

    /**
     * Define relationships for students and counselors.
     */
    public function counselorProfile()
    {
        return $this->hasOne(CounselorProfile::class);
    }

    public function counselorAppointments()
    {
        return $this->hasMany(Appointment::class, 'counselor_id');
    }

    public function studentAppointments()
    {
        return $this->hasMany(Appointment::class, 'student_id');
    }
    public function goals()
    {
        return $this->hasMany(Goal::class);
    }
 
}
