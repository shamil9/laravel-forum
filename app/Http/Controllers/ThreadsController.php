<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Inspections\Spam;
use App\Thread;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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
        $threads = Thread::filter($filters)
            ->latest()
            ->paginate(20);
        $channel = null;
        $trending = array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));

        if (request()->wantsJson()) {
            return $threads;
        }

        return view('threads/index', compact('threads', 'channel', 'trending'));
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

        Redis::zincrby('trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path'  => route('threads.show', [
                'channel' => $thread->channel_id,
                'thread'  => $thread,
            ]),
        ]));

        return view('threads/show', [
            'thread'  => $thread,
            'replies' => $thread->replies()->paginate(20),
        ]);
    }

    /**
     * @param Request $request
     * @param Spam    $spam
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, Spam $spam)
    {
        $this->validate($request, [
            'title'      => 'required',
            'body'       => 'required',
            'channel_id' => 'required|exists:channels,id',
        ]);

        $spam->check($request->body);

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
