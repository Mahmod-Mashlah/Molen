<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'fathername',
        'lastname',
        'birthdate',
        'gender',
        'phone',
        'address',
        'email',
        'password',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
    public function allergy()
    {
        return $this->hasMany(Allergy::class);
    }

    public function analizing()
    {
        return $this->hasMany(Analyzing::class);
    }

    public function exam()
    {
        return $this->hasMany(Exam::class);
    }

    public function family()
    {
        return $this->hasMany(Family::class);
    }

    public function note()
    {
        return $this->hasMany(Note::class);
    }

    public function  personalizing()
    {
        return $this->hasMany(Personalizing::class);
    }

    public function  prescription()
    {
        return $this->hasMany(Prescription::class);
    }

    public function  ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function  ray()
    {
        return $this->hasMany(Ray::class);
    }

    public function  reservation()
    {
        return $this->hasMany(Reservation::class);
    }


    public function  state()
    {
        return $this->hasMany(State::class);
    }

    public function  sugarTest()
    {
        return $this->hasMany(SugarTest::class);
    }


    public function  surgery()
    {
        return $this->hasMany(Surgery::class);
    }


    public function  treatment()
    {
        return $this->hasMany(Treatment::class);
    }


    public function  vaccine()
    {
        return $this->hasMany(Vaccine::class);
    }
}
