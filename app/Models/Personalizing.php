<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personalizing extends Model
{
    use HasFactory;

    protected $fillable = [

        'user_id',

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
