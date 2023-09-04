<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'doctor_id',

        'medicine_name',
        'drug_type',
        'repetition',
        'take_times',

    ];

}
