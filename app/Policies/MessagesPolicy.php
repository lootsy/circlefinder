<?php

namespace App\Policies;

use App\Circle;
use App\User;
use App\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create messages.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, Circle $circle)
    {
        return $circle->joined($user) || $user->moderator();
    }

    /**
     * Determine whether the user can update the message.
     *
     * @param  \App\User  $user
     * @param  \App\Message  $message
     * @return mixed
     */
    public function update(User $user, Message $message)
    {
        return ($message->user_id == $user->id) || $user->moderator();
    }

    /**
     * Determine whether the user can delete the message.
     *
     * @param  \App\User  $user
     * @param  \App\Message  $message
     * @return mixed
     */
    public function delete(User $user, Message $message)
    {
        return ($message->user_id == $user->id) || $user->moderator();
    }
}
