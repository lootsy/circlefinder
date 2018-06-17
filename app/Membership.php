<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'type', 
        'begin',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
