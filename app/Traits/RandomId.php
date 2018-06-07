<?php

namespace App\Traits;
use Illuminate\Support\Str;

trait RandomId
{
    public function generateUniqueId()
    {
        $this->uuid = (string) Str::orderedUuid();
        $this->save();
    }

    public static function withUuid($uuid)
    {
        return self::where('uuid', $uuid);
    }
}