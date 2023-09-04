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

        'user_id',
    ];
}
