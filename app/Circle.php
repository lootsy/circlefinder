<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\Traits\RandomId;


class Circle extends Model
{
    use RandomId;

    protected $fillable = [
        'type', 
        'title',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function($circle) {
            $circle->generateUniqueId();
        });

        static::deleting(function($circle) {
            $circle->languages()->detach();

            $circle->memberships()->delete();
        });
    }

    public function languages()
    {
        return $this->belongsToMany(\App\Language::class);
    }

    public function memberships()
    {
        return $this->hasMany(\App\Membership::class);
    }

    public function users()
    {
        return $this->hasManyThrough('App\User', 'App\Membership', 'circle_id', 'id', 'id', 'user_id');
    }

    public function canDelete()
    {
        if($this->memberships()->count() > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function join($membership_data, $user)
    {
        if(\App\Membership::where(['circle_id' => $this->id, 'user_id' => $user->id])->count() > 0)
        {
            return null;
        }

        $membership = new \App\Membership;

        $membership->fill($membership_data);
        
        $membership->user_id = $user->id;

        $membership->circle_id = $this->id;

        $membership->save();

        return $membership;
    }

    public function leave($user)
    {
        $memberships = \App\Membership::where(['circle_id' => $this->id, 'user_id' => $user->id]);
        
        if($memberships->count() > 0)
        {
            $memberships->first()->delete();
        }
    }

    
}
