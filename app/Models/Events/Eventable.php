<?php

namespace App\Models\Events;

use App\Models\User;

trait Eventable
{
    protected $eventableEvents = [
        'created', 'updated',
    ];

    public static function bootEventable()
    {
        $eventsMap = (new static)->eventableEvents;

        foreach ($eventsMap as $modelEvent) {
            static::{$modelEvent}(function ($model) use ($modelEvent) {
                $model->recordEvent($modelEvent);
            });
        }

        static::deleting(function ($model) {
            $model->bucket->events()->where('recording_id', $model->id)->delete();
        });
    }

    protected function recordEvent(string $modelEvent)
    {
        if (! $this->shouldRecordEvent($modelEvent)) {
            return;
        }

        $this->bucket->events()->create([
            'recording' => $this,
            'recordable' => $this->recordable,
            'creator' => $this->eventCreator($modelEvent),
            'event_type' => $this->recordable->getRecordableEvent($modelEvent),
        ]);
    }

    protected function shouldRecordEvent(string $eventType)
    {
        if (! $this->recordable instanceof RecordsEvents) {
            return false;
        }

        return $this->recordable->shouldRecordEvent($eventType);
    }

    protected function eventCreator(string $modelEvent): ?User
    {
        if ($modelEvent === 'created') {
            return $this->creator;
        }

        return auth()->user();
    }
}
