<?php

namespace App;

use App\Activity as Activity;
use App\Reply as Reply;
use App\Thread as Thread;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
        'confirmed',
        'confirmation_token',
    ];

    protected $casts = [
        'confirmed' => 'boolean',
        'is_admin'  => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function threads()
    {
        return $this->hasMany(Thread::class)->latest();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }

    public function getAvatarAttribute()
    {
        return asset($this->avatar_path ?: 'avatars/default.png');
    }
}
