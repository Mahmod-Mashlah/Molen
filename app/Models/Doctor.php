<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [

        'specialist_on',
        'certifactions',
        'location',
        'image',
        'birthdate',

        'user_id',a
    ];

    public function allergy()
    {
        return $this->hasMany(Allergy::class);
    }

    public function analizing()
    {
        return $this->hasMany(Analyzing::class);
    }

    public function days()
    {
        return $this->belongsToMany(Day::class, 'work_times',);
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
