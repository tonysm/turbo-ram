<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bucket()
    {
        return $this->belongsTo(Bucket::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    public function recording()
    {
        return $this->belongsTo(Recording::class);
    }

    public function recordable()
    {
        return $this->morphTo();
    }

    public function setRecordingAttribute(Recording $recording)
    {
        $this->recording()->associate($recording);
    }

    public function setRecordableAttribute($recordable)
    {
        $this->recordable()->associate($recordable);
    }

    public function setCreatorAttribute(?User $user)
    {
        $this->creator()->associate($user);
    }
}
