<?php

namespace App\Models\Events;

interface RecordsEvents
{
    public function shouldRecordEvent(string $eventType): bool;
    public function getRecordableEvent(string $modelEvent): string;
}
