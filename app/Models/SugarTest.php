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

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
