<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surgery extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',
        'doctor_id',

        'sugery_name',
        'result',
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
