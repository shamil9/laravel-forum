<?php

namespace App;


use Illuminate\Support\Facades\Redis;

class Trending
{
    public static function get()
    {
        return collect(array_map('json_decode', Redis::zrevrange(static::cacheKey(), 0, 4)));
    }

    public function push($thread)
    {
        Redis::zincrby(static::cacheKey(), 1, $this->encodeKey($thread));
    }

    public static function cacheKey()
    {
        return app()->environment('testing') ?
            'testing_trending_threads' :
            'trending_threads';
    }

    public function remove($thread)
    {
        return Redis::zrem(static::cacheKey(), $this->encodeKey($thread));
    }

    private function encodeKey($thread)
    {
        return json_encode([
            'title' => $thread->title,
            'path'  => route('threads.show', [$thread->channel, $thread]),
        ]);
    }
}