<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recording extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bucket()
    {
        return $this->belongsTo(Bucket::class);
    }

    public function parentRecording()
    {
        return $this->belongsTo(Recording::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function recordable()
    {
        return $this->morphTo();
    }

    public function setBucketAttribute(Bucket $bucket)
    {
        $this->bucket()->associate($bucket);
    }

    public function setParentRecordingAttribute(?Recording $parent)
    {
        if (! $parent) return;

        $this->parentRecording()->associate($parent);
    }

    public function setCreatorAttribute(User $creator)
    {
        $this->creator()->associate($creator);
    }

    public function setRecordableAttribute($recordable)
    {
        $this->recordable()->associate($recordable);
    }
}
