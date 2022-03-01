<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{
    use HasFactory;

    public function bucketable()
    {
        return $this->morphTo();
    }

    public function recordings()
    {
        return $this->hasMany(Recording::class);
    }

    public function setBucketableAttribute($bucketable)
    {
        $this->bucketable()->associate($bucketable);
    }
}
