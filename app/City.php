<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'code',
        'timezone',
        'latitude',
        'longitude',
    ];

    public function __toString()
    {
        return sprintf('%s', $this->name);
    }

    public function state()
    {
        return $this->belongsTo(\App\State::class);
    }
}
