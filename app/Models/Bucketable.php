<?php

namespace App\Models;

trait Bucketable
{
    public static function bootBucketable()
    {
        static::created(function ($bucketable) {
            if (Bucket::$autoCreateOnBucketables ?? false) {
                $bucketable->ensureBucketIsCreated();
            }
        });
    }

    public function bucket()
    {
        return $this->morphOne(Bucket::class, 'bucketable');
    }

    protected function ensureBucketIsCreated()
    {
        if ($this->bucket) {
            return;
        }

        $this->setRelation('bucket', $this->bucket()->create());
    }
}
