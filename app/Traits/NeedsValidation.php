<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait NeedsValidation
{
    public static function validationRules($except = null)
    {
        return [];
    }
}
