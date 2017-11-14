<?php

namespace App;

use App\Filters\Filter;
use App\ThreadSubscription;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadUpdated;

class Thread extends Model
{
    use RecordActivity;

    protected $guarded = [];
    protected $with = [];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        $this->notifySubscribers($reply);

        return $reply;
    }

    public function notifySubscribers($reply)
    {
        foreach ($this->subscriptions as $subscription) {
            if ($subscription->user_id != $reply->user_id) {
                $subscription->user->notify(new ThreadUpdated($this, $reply));
            }
        }
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, Filter $filters)
    {
        $filters->apply($query);
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where(['user_id' => $userId ?: auth()->id()])
            ->delete();
    }

    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()
                    ->where(['user_id' => auth()->id()])
                    ->exists();
    }
}
