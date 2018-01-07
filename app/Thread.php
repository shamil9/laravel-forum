<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Filters\Filter;
use App\Notifications\ThreadUpdated;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordActivity;

    protected $guarded = [];
    protected $with = [];
    protected $appends = ['best_reply_id'];
    protected $casts = ['locked' => 'boolean'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
            $thread->trending()->remove($thread);
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

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function toggleLock()
    {
        static::update(['locked' => ! $this->locked]);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

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

    public function scopeFilter($query, Filter $filters)
    {
        $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id(),
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

    public function hasUpdates($user = null)
    {
        if (! auth()->check()) {
            return;
        }

        $user = $user ?: auth()->user();
        $key = $this->lastVisitTimeKey($user->id);

        return $this->updated_at > cache($key);
    }

    public function lastVisitTimeKey($user = null)
    {
        $user = $user ?: auth()->user();

        return sprintf('user.%s.visits.%s', $user, $this->id);
    }

    public function visits()
    {
        return new RecordsVisits($this);
    }

    public function trending()
    {
        return new Trending();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function bestReply()
    {
        return $this->hasOne(BestReply::class);
    }

    public function getBestReplyIdAttribute()
    {
        return $this->bestReply()->value('reply_id');
    }

    public function setSlugAttribute($value)
    {
        $value = str_slug($value);

        if (! static::whereSlug($value)->exists()) {
            return $this->attributes['slug'] = $value;
        }

        return $this->attributes['slug'] = $value . '-' . (new \DateTime)->getTimestamp();
    }
}
