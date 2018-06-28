<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    protected $fillable = [
        'time',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday'
    ];

    public static function validationRules($except = null)
    {
        $rules = [
            #'time' => 'required|in:' . implode(',', config('circle.defaults.types')),
        ];

        if ($except) {
            $rules = array_except($rules, $except);
        }

        return $rules;
    }
}
