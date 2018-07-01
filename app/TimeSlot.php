<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'time',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday',
        'sunday',
    ];

    protected $casts = [
        'monday' => 'array',
        'tuesday' => 'array',
        'wednesday' => 'array',
        'thursday' => 'array',
        'friday' => 'array',
        'saturday' => 'array',
        'sunday' => 'array',
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

    public function atTime($time)
    {
        foreach (\App\TimeTable::getDayList() as $day) {
            if (is_array($this->$day) && in_array($time, $this->$day)) {
                return true;
            }
        }

        return false;
    }
}
