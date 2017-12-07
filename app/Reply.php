<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use RecordActivity;

    protected $guarded = [];
    protected $with = ['owner', 'favorites'];
    protected $appends = ['favoritesCount', 'isFavorited'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->favorites()->get()->each->delete();
        });

        static::created(function ($reply) {
            $reply->thread->increment('replies_count');
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
        return $this->MorphMany(Favorite::class, 'favorited');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function favorite()
    {
        if ($this->favorites()->exists()) {
            return;
        }

        $this->favorites()->create(['user_id' => auth()->id()]);
    }

    public function unfavorite()
    {
        $this->favorites()->where(['user_id' => auth()->id()])->get()->each->delete();
    }

    public function isFavorited()
    {
        return !!$this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function setBodyAttribute($body)
    {
        $newBody = preg_replace(
            '/@([\w\-]+)/',
            '<a href="' . route('profile.show', [auth()->user()]) . '">$0</a>',
            $body
        );

        return $this->attributes['body'] = $newBody;
    }

    public function path()
    {
        return route('threads.show', [
            'thread' => $this->thread->id,
            'channel' => $this->thread->channel_id
        ]);
    }

    public function isJustPosted()
    {
        return !!$this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all('/@([\w\-]+)/', $this->body, $matches);

        return $matches[1];
    }
}
