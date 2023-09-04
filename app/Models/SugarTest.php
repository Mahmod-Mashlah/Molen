<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SugarTest extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'doctor_id',

        'type',
        'result',
        'date',
        'time',

    ];
}
