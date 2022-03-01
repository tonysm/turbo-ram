<?php

namespace App\Policies;

use App\Models\Bucket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BucketPolicy
{
    use HandlesAuthorization;

    public function addPost(User $user, Bucket $bucket)
    {
        return $user->belongsToTeam($bucket->bucketableTeam());
    }
}
