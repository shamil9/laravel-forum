<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * @param ThreadFilters $filters
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ThreadFilters $filters)
    {
        $threads = Thread::filter($filters)->latest()->get();
        $channel = null;

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads/index', compact('threads', 'channel'));
    }

    /**
     * @param Channel $channel
     * @param Thread  $thread
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Channel $channel, Thread $thread)
    {
        cache()->forever($thread->lastVisitTimeKey(), Carbon::now());

        return view('threads/show', [
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(20)
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'      => 'required',
            'body'       => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create([
            'user_id'    => auth()->id(),
            'channel_id' => $request->channel_id,
            'body'       => $request->body,
            'title'      => $request->title,
        ]);

        return redirect(route('threads.show', [
            'thread'  => $thread->id,
            'channel' => $thread->channel->id,
        ]))->with('flash', 'Thread created');
    }

    /**
     * @param Channel $channel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Channel $channel)
    {
        return view('threads/create', compact('channel'));
    }

    /**
     * @param  Channel $channel
     * @param  Thread  $thread
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('destroy', $thread);

        $thread->delete();

        return redirect(route('all.threads.index'));
    }
}
