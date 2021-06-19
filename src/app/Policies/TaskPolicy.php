<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Task $task)
    {
        return $user->is($task->getProject->user) || $task->getProject()->members->contains($user);
    }

    public function update(User $user, Task $task)
    {
        return $user->is($task->getProject->user) || $task->getProject->members->contains($user);
    }

    public function destroy(User $user, Task $task)
    {
        return $user->is($task->getProject->user);
    }
}