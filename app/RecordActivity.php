<?php

namespace App;

use App\Activity;

trait RecordActivity
{
    protected static function bootRecordActivity()
    {
        if (auth()->guest()) {
            return;
        }

        foreach (static::getEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function ($model) {
            $model->activity()->delete();
        });
    }

    protected static function getEvents()
    {
        return ['created'];
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth()->id(),
        ]);
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }

    protected function activity()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}
