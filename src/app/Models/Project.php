<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Project.
 *
 * @property int                                                         $id
 * @property string                                                      $title
 * @property string                                                      $description
 * @property null|string                                                 $notes
 * @property int                                                         $owner
 * @property null|\Illuminate\Support\Carbon                             $created_at
 * @property null|\Illuminate\Support\Carbon                             $updated_at
 * @property Activity[]|\Illuminate\Database\Eloquent\Collection         $activity
 * @property null|int                                                    $activity_count
 * @property \App\Models\Task[]|\Illuminate\Database\Eloquent\Collection $tasks
 * @property null|int                                                    $tasks_count
 * @property User                                                        $user
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Project extends Model
{
    use HasFactory;
    /**
     * old attributes to be used after update or delete.
     */
    public iterable $oldAttributes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * gets the path of a project may be on slug or id or anything else.
     */
    public function path(): string
    {
        return "/project/{$this->id}";
    }

    /**
     * one user has many projects
     * get the project owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    /**
     * one project has many tasks
     * get the tasks related to any project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(\App\Models\Task::class, 'project')->latest('updated_at');
    }

    /**
     * adding task to a project.
     *
     * @param string     $body
     * @param null|Model $user
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addTask($body, $user = null)
    {
        return $this->tasks()->create(['body' => $body, 'owner' => $user ?? auth()->id(), 'status' => 0]);
    }

    /**
     * polymorphic relation between model and activity.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'activitable');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }

    public function invite(User $user)
    {
        $this->members()->attach($user);
    }
}