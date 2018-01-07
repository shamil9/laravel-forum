<?php

namespace App\Http\Controllers;

use App\Thread;

class ThreadLockController extends Controller
{

    /**
     * Lock or unlock thread
     *
     * @param Thread $thread
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function lock(Thread $thread)
    {
        try {
            $thread->toggleLock();

            return response('Thread locked', 200);
        } catch (\Exception $e) {
            return response('Error! Thread can be locked only by its owner', 403);
        }
    }


    /**
     * Lock or unlock thread
     *
     * @param Thread $thread
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function unlock(Thread $thread)
    {
        try {
            $thread->toggleLock();

            return response('Thread unlocked', 200);
        } catch (\Exception $e) {
            return response('Error! Thread can be unlocked only by its owner', 403);
        }
    }
}
