<?php

namespace App\Models\Events;

trait HasRecordableEvents
{
    public function shouldRecordEvent(string $event): bool
    {
        if (property_exists($this, 'recordableEvents')) {
            return in_array($event, $this->recordableEvents);
        }

        return true;
    }

    public function getRecordableEvent(string $modelEvent): string
    {
        $resource = str(class_basename($this))->snake()->toString();

        return "{$resource}_{$modelEvent}";
    }
}
