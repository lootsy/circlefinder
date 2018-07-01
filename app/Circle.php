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
        'limit',
        'description',
        'begin',
    ];

    protected $casts = [
        'completed' => 'boolean',
    ];

    public static function validationRules($except = null)
    {
        $rules = [
            'type' => 'required|in:' . implode(',', config('circle.defaults.types')),
            'begin' => 'required|date',
            'languages' => 'exists:languages,code',
        ];

        if ($except) {
            $rules = array_except($rules, $except);
        }

        return $rules;
    }

    public function __toString()
    {
        return sprintf('Circle %d', $this->id);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($circle) {
            $circle->generateUniqueId();
        });

        static::deleting(function ($circle) {
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

    public function membershipOf($user)
    {
        return \App\Membership::where(['circle_id' => $this->id, 'user_id' => $user->id])->first();
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function users()
    {
        return $this->hasManyThrough('App\User', 'App\Membership', 'circle_id', 'id', 'id', 'user_id');
    }

    public function deletable()
    {
        if ($this->memberships()->count() > 0) {
            return false;
        } else {
            return true;
        }
    }

    public function full()
    {
        if ($this->memberships()->count() >= $this->limit) {
            return true;
        } else {
            return false;
        }
    }

    public function joinable($user = null)
    {
        if ($user && $this->joined($user)) {
            return false;
        }

        return !$this->full() && !$this->completed;
    }

    public function joined($user)
    {
        if (\App\Membership::where(['circle_id' => $this->id, 'user_id' => $user->id])->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function join($membership_data, $user)
    {
        if ($this->joinable($user) == false) {
            return null;
        }

        $membership = new \App\Membership;
        $membership->fill($membership_data);
        $membership->user_id = $user->id;
        $membership->circle_id = $this->id;
        $membership->save();

        if (key_exists('languages', $membership_data)) {
            $membership->languages()->attach($membership_data['languages']);
        }

        if ($this->memberships()->count() >= $this->limit) {
            $this->complete();
        }

        return $membership;
    }

    public function joinWithDefaults($user)
    {
        $default_membership_data = [
            'type' => $this->type,
            'begin' => $this->begin,
            'languages' => $this->languages,
        ];

        return $this->join($default_membership_data, $user);
    }

    public function leave($user)
    {
        $memberships = \App\Membership::where(['circle_id' => $this->id, 'user_id' => $user->id]);

        if ($memberships->count() > 0) {
            $memberships->first()->delete();
        }
    }

    public function ownedBy($user)
    {
        return $this->user->id == $user->id;
    }

    public function complete()
    {
        $this->completed = true;
        $this->save();
    }

    public function uncomplete()
    {
        $this->completed = false;
        $this->save();
    }

    public static function createAndModify($user, $request)
    {
        $request->merge(['limit' => config('circle.defaults.limit')]);

        $item = $user->circles()->create($request->all());

        if ($request->languages) {
            $languages = \App\Language::whereIn('code', array_values($request->languages))->get();
            $item->languages()->attach($languages);
        }

        if ($user->moderator() == false) {
            $item->joinWithDefaults($user);
        }

        return $item;
    }

    public function updateAndModify($request)
    {
        $this->update($request->all());

        if ($request->languages) {
            $languages = \App\Language::whereIn('code', array_values($request->languages))->get();
            $this->languages()->sync($languages);
        } else {
            $this->languages()->detach();
        }
    }

    public function link($title = null, $class = null)
    {
        $link_title = $title ? $title : (string) $this;

        if ($class) {
            $class = sprintf(' class="%s"', $class);
        }

        $link = sprintf('<a href="%s"%s>%s</a>', route('circles.show', ['uuid' => $this->uuid]), $class, $link_title);

        return $link;
    }

    public function goodTitle()
    {
        if ($this->title) {
            return $this->title . ' (' . $this . ')';
        } else {
            return (string) $this;
        }
    }
}
