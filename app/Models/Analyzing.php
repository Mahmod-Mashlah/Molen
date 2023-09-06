<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analyzing extends Model
{
    use HasFactory;

    protected $fillable = [

        'doctor_id',
        'user_id',

        'name',
        'result',
        'naturalizing',
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
