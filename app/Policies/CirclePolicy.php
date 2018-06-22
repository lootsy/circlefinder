<?php

namespace App\Policies;

use App\User;
use App\Circle;
use Illuminate\Auth\Access\HandlesAuthorization;

class CirclePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the circle.
     *
     * @param  \App\User  $user
     * @param  \App\Circle  $circle
     * @return mixed
     */
    public function view(User $user, Circle $circle)
    {
        return true;
    }

    /**
     * Determine whether the user can create circles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the circle.
     *
     * @param  \App\User  $user
     * @param  \App\Circle  $circle
     * @return mixed
     */
    public function update(User $user, Circle $circle)
    {
        return $circle->ownedBy($user) || $user->hasRole('moderator');
    }

    /**
     * Determine whether the user can delete the circle.
     *
     * @param  \App\User  $user
     * @param  \App\Circle  $circle
     * @return mixed
     */
    public function delete(User $user, Circle $circle)
    {
        return $circle->ownedBy($user) || $user->hasRole('moderator');
    }
}
