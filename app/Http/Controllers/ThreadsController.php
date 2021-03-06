<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Http\Requests\StoreThread;
use App\Inspections\Spam;
use App\Thread;
use Carbon\Carbon;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'valid-user'])->except(['index', 'show']);
    }

    /**
     * @param ThreadFilters $filters
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ThreadFilters $filters)
    {
        $threads = Thread::filter($filters)
            ->with('owner')
            ->withCount('replies')
            ->latest()
            ->paginate(20);
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
     * @throws \Exception
     */
    public function show(Channel $channel, Thread $thread)
    {
        cache()->forever($thread->lastVisitTimeKey(), Carbon::now());

        $thread->trending()->push($thread);

        return view('threads/show', [
            'thread'  => $thread,
            'replies' => $thread->replies()->paginate(20),
        ]);
    }

    /**
     * @param StoreThread $request
     * @param Spam        $spam
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreThread $request, Spam $spam)
    {
        $thread = Thread::create([
            'user_id'    => auth()->id(),
            'channel_id' => $request->channel_id,
            'body'       => $request->body,
            'title'      => $request->title,
            'slug'       => $request->title,
        ]);

        return redirect(route('threads.show', [
            'thread'  => $thread,
            'channel' => $thread->channel,
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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Channel $channel, Thread $thread)
    {
        $this->authorize('destroy', $thread);

        $thread->delete();

        return redirect(route('all.threads.index'));
    }
}
