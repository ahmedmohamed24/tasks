<?php

namespace App\Observers;

use App\Models\Project;
use App\Providers\RecordActivity;

class ProjectObserver
{
    use RecordActivity;

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project)
    {
        $this->activityCreate($project, false, 'new project created');
    }

    /**
     * just before data is updating take an instance of them.
     */
    public function updating(Project $project)
    {
        $project->oldAttributes = $project->getOriginal();
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project)
    {
        $this->activityCreate($project, true, 'new project created');
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project)
    {
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project)
    {
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project)
    {
    }
}