<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User.
 *
 * @property int                                                                                                       $id
 * @property string                                                                                                    $name
 * @property string                                                                                                    $email
 * @property null|\Illuminate\Support\Carbon                                                                           $email_verified_at
 * @property string                                                                                                    $password
 * @property null|string                                                                                               $remember_token
 * @property null|\Illuminate\Support\Carbon                                                                           $created_at
 * @property null|\Illuminate\Support\Carbon                                                                           $updated_at
 * @property \App\Models\Activity[]|\Illuminate\Database\Eloquent\Collection                                           $activities
 * @property null|int                                                                                                  $activities_count
 * @property \Illuminate\Notifications\DatabaseNotification[]|\Illuminate\Notifications\DatabaseNotificationCollection $notifications
 * @property null|int                                                                                                  $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection|Project[]                                                        $projects
 * @property null|int                                                                                                  $projects_count
 * @property \Illuminate\Database\Eloquent\Collection|Task[]                                                           $tasks
 * @property null|int                                                                                                  $tasks_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner')->orderBy('updated_at', 'desc');
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Project::class, 'owner', 'project', 'id', 'id');
    }

    public function activities()
    {
        // return $this->hasMany(\App\Models\Activity::class, 'owner')->orderBy('updated_at', 'desc');
        $projectIds = $this->availableProjects()->pluck('id');
        $projects = $projectIds->map(function ($projectId) {
            return Project::find($projectId)->tasks->pluck('id');
        })->toArray();
        $tasksId = [];
        foreach ($projects as $project => $value) {
            if ($value) {
                foreach ($value as $temp) {
                    \array_push($tasksId, $temp);
                }
            }
        }

        return Activity::whereHasMorph('activitable', '*', function (Builder $query, $type) use ($projectIds, $tasksId) {
            $arrayOfValues = Task::class === $type ? $tasksId : $projectIds;
            $query->whereIn('activitable_id', $arrayOfValues);
        })->orderBy('created_at', 'desc');
    }

    public function invitedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_members');
    }

    public function availableProjects()
    {
        return Project::where('owner', $this->id)->orWhereHas('members', function ($query) {
            $query->where('user_id', $this->id);
        });
    }
}