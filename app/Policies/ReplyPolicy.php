<?php

namespace App\Policies;

use App\Reply;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    public function store(User $user)
    {
        if (!$user->lastReply()->count()) {
            return true;
        }

        return !$user->lastReply->isJustPosted();
    }

    public function destroy(User $user, Reply $reply)
    {
        return $user->id == $reply->user_id;
    }

    public function update(User $user, Reply $reply)
    {
        return $user->id == $reply->user_id;
    }
}
