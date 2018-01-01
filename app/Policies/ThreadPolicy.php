<?php

namespace App\Policies;

use App\Thread;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThreadPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Thread $thread)
    {
        return $thread->user_id == auth()->id();
    }

    public function destroy(User $user, Thread $thread)
    {
        return $thread->user_id == auth()->id();
    }
}
