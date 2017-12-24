<?php

namespace App;


use Illuminate\Support\Facades\Redis;

class Trending
{
    public static function get()
    {
        return array_map('json_decode', Redis::zrevrange(static::cacheKey(), 0, 4));
    }

    public function push($thread)
    {
        Redis::zincrby(static::cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path'  => route('threads.show', [
                'channel' => $thread->channel_id,
                'thread'  => $thread,
            ]),
        ]));
    }

    public static function cacheKey()
    {
        return app()->environment('testing') ?
            'testing_trending_threads' :
            'trending_threads';
    }
}