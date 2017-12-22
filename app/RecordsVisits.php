<?php
/**
 * Created by PhpStorm.
 * User: Shamil
 * Date: 22/12/2017
 * Time: 11:22
 */

namespace App;


use Illuminate\Support\Facades\Redis;

class RecordsVisits
{

    private $thread;

    /**
     * RecordsVisits constructor.
     *
     * @param Thread $thread
     */
    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    /**
     * Return views count
     *
     * @return int
     */
    public function count()
    {
        return Redis::get($this->cacheKey()) ?? 0;
    }

    /**
     * Reset view count
     *
     * @return $this
     */
    public function reset()
    {
        Redis::del($this->cacheKey());

        return $this;
    }

    /**
     * Set redis key name
     *
     * @return string
     */
    public function cacheKey()
    {
        return app()->environment('testing') ?
            "testing.thread.{$this->thread->id}.views" :
            "thread.{$this->thread->id}.views";
    }

    /**
     * Increment thread view count by 1
     *
     * @return $this
     */
    public function record()
    {
        Redis::incr($this->cacheKey());

        return $this;
    }
}