<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function __toString()
    {
        return sprintf('%s', $this->name);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($state) {
            $state->cities()->delete();
        });
    }

    public function cities()
    {
        return $this->hasMany(\App\City::class);
    }
}
