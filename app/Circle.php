<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Circle extends Model
{
    protected $fillable = [
        'type', 
        'title',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($circle)
        {
            #$circle->languages()->detach();

        });
    }

    public function memberships()
    {
        
    }

    public function users()
    {
        
    }
}
