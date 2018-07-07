<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name',
        'timezone',
        'latitude',
        'longitude',
    ];

    public static function validationRules($except = null)
    {
        $rules = [
            'name' => 'required',
            'timezone' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
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

    public function country()
    {
        return $this->belongsTo(\App\Country::class);
    }
}
