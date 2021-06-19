<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task.
 *
 * @property int                                                 $id
 * @property string                                              $body
 * @property int                                                 $status
 * @property int                                                 $project
 * @property int                                                 $owner
 * @property null|\Illuminate\Support\Carbon                     $created_at
 * @property null|\Illuminate\Support\Carbon                     $updated_at
 * @property Activity[]|\Illuminate\Database\Eloquent\Collection $activity
 * @property null|int                                            $activity_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereProject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
    use HasFactory;
    /**
     * old attributes that is used to log system.
     */
    public iterable $oldAttributes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['getProject'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * get the path of the task.
     *
     * @return string
     */
    public function path()
    {
        return "project/{$this->project}/task/{$this->id}";
    }

    /**
     * get the parent project of a given task
     * one project has many tasks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getProject()
    {
        return $this->belongsTo(\App\Models\Project::class, 'project');
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
}