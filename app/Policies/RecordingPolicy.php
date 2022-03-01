<?php

namespace App\Policies;

use App\Models\Recording;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecordingPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Recording $recording)
    {
        return $user->belongsToTeam($recording->bucket->bucketableTeam());
    }
}
