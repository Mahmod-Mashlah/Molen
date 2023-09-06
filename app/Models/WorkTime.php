<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkTime extends Model
{
    use HasFactory;

    protected $fillable = [

        'doctor_id',
        'day_id',

        'start_time',
        'end_time',
    ];

    /**
     *
     * No relations here because
     * the relation is many to many ,
     * you can see relation in Day & Doctor Models
     *  */
}
