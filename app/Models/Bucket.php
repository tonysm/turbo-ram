<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Bucket extends Model
{
    use HasFactory;

    public function bucketable()
    {
        return $this->morphTo();
    }

    public function bucketableTeam()
    {
        return $this->bucketable->teamForBucketable();
    }

    public function recordings()
    {
        return $this->hasMany(Recording::class);
    }

    public function setBucketableAttribute($bucketable)
    {
        $this->bucketable()->associate($bucketable);
    }

    public function record(Model $recordable, $children = null, $parent = null, $creator = null, $status = 'active', array $options = [])
    {
        $creator ??= auth()->user();

        return DB::transaction(function () use ($recordable, $children, $parent, $status, $creator, $options) {
            $recordable->saveOrFail();

            $options = array_merge($options, [
                'recordable' => $recordable,
                'parent_recording' => $parent,
                'status' => $status,
                'creator' => $creator,
            ]);

            return tap($this->recordings()->create($options), function ($recording) use ($children, $status, $creator) {
                foreach (Arr::wrap($children ?: []) as $child) {
                    $this->record($child, parent: $recording, status: $status, creator: $creator);
                }
            });
        });
    }
}
