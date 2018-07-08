<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
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

        static::deleting(function ($country) {
            $country->states()->delete();
        });
    }

    public function states()
    {
        return $this->hasMany(\App\State::class);
    }
}
