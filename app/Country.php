<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name',
        'name_common',
        'iso',
        'timezone'
    ];

    public static function validationRules($except = null)
    {
        $rules = [
            'name' => 'required',
            'name_common' => 'required',
            'iso' => 'required',
            'timezone' => 'required'
        ];

        if ($except) {
            $rules = array_except($rules, $except);
        }

        return $rules;
    }

    public function __toString()
    {
        return sprintf('%s', $this->name);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($circle) {
            $circle->cities()->delete();
        });
    }

    public function cities()
    {
        return $this->hasMany(\App\City::class);
    }
}
