<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Bucket extends Model
{
    use HasFactory;

    public static $autoCreateOnBucketables = true;

    public static function booted()
    {
        static::created(function (Bucket $bucket) {
            $bucket->ensureBlogIsCreatedAndDocked();
        });
    }

    public static function withoutAutoCreation(callable $scope): mixed
    {
        static::$autoCreateOnBucketables = false;

        try {
            return $scope();
        } finally {
            static::$autoCreateOnBucketables = true;
        }
    }

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

    public function dock()
    {
        return $this->recordings()
            ->dock();
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
                'parent' => $parent,
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

    protected function ensureBlogIsCreatedAndDocked(): void
    {
        $owner = $this->bucketableTeam()->owner;

        $dock = $this->record(new Dock(), creator: $owner);

        $this->record(new Blog([
            'name' => 'Blog',
            'slug' => str($this->bucketableTeam()->name)->slug() . '-' . strtolower(str()->random(6)),
        ]), creator: $owner, parent: $dock);
    }
}
