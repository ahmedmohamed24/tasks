<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPermission
{
    use HandlesAuthorization;
    /**
     *  make sure the project owner only can delete activities. not any member make change and delete logs
     * @param User $user
     * @param Activity $activity
     */ 
    public function deleteActivity(User $user,Activity $activity)
    {
        if($activity->activitable_type==='App\Models\Project')
            return $activity->activitable->owner==$user->id;
        elseif($activity->activitable_type=='App\Models\Task')
            return User::find($activity->activitable->owner)===$user;
        else
            return $activity->owner==$user->id;
    }
}
