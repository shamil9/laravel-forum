<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BestReply extends Model
{
    protected $fillable = ['thread_id', 'reply_id'];
}
