<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'doctor_id',

        'hight',
        'width',
        'temperature',
        'pressure',
        'blood_oxygen',
        'date',

    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
