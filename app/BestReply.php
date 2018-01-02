<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BestReply extends Model
{
    protected $fillable = ['thread_id', 'reply_id'];

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function reply()
    {
        return $this->belongsTo(Reply::class);
    }
}
