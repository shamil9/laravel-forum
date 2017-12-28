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

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
            $this->attributes['slug'] = $slug;
        }

        $this->attributes['slug'] = $slug;

    }

    private function incrementSlug($value)
    {
        $max = static::whereTitle($this->title)->latest('id')->value('slug');

        // Increment last digit on duplicate slug
        if (is_numeric($max[-1])) {
            return preg_replace_callback('/(\d+)$/', function ($matches) {
                return $matches[0] + 1;
            }, $max);
        }

        return "{$value}-2";
    }
}
