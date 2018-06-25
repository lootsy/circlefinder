<?php

namespace App\Policies;

use App\User;
use App\Membership;
use Illuminate\Auth\Access\HandlesAuthorization;

class MembershipPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the membership.
     *
     * @param  \App\User  $user
     * @param  \App\Membership  $membership
     * @return mixed
     */
    public function update(User $user, Membership $membership)
    {
        return $membership->ownedBy($user);
    }
}
