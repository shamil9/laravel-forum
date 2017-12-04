<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReply;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Thread $thread
     * @return \Illuminate\Http\Response
     * @internal param Request $request
     */
    public function store(Thread $thread, StoreReply $request)
    {
        $thread->addReply([
            'body'    => request('body'),
            'user_id' => auth()->id(),
        ]);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Reply succesfully added']);
        }

        return back()->with('flash', 'Reply succesfully added');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Reply                    $reply
     * @return void
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->update(request(['body']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Reply $reply
     * @return \Illuminate\Http\Response
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);

        $reply->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Reply succesfully deleted']);
        }

        return back();
    }
}
