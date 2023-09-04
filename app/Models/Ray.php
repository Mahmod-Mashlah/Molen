<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ray extends Model
{
    use HasFactory;
    protected $fillable = [

        'user_id',
        'doctor_id',

        'ray_name',
        'result',
        'date',

    ];
}
