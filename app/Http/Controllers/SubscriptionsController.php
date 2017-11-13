<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    /**
     * Store subscription
     * @param  Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function store(Thread $thread)
    {
        $thread->subscribe();
    }

    public function destroy(Thread $thread)
    {
        $thread->unsubscribe();
    }
}
