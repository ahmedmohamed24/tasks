<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class RecordActivityTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    // @test
    public function testCreateProject()
    {
        $this->withoutExceptionHandling();
        Project::factory()->create();
        $this->assertDatabaseCount('activities', 1);
    }

    // @test
    public function testActivityShouldHaveADescription()
    {
        $this->withoutExceptionHandling();
        Project::factory()->create();
        $this->assertDatabaseHas('activities', ['description' => 'new project created']);
    }

    // @test
    public function testUpdateProject()
    {
        // $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $data = ['notes' => 'tested'];
        $project->update($data);
        // $this->patch($project->path(),$data);
        $this->assertDatabaseCount('activities', 2);
    }

    // @test
    public function testCreateTask()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $project->addTask('test');
        $this->assertDatabaseCount('activities', 2);
    }

    // @test
    public function testUpdateTask()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $project->addTask('test');
        $project->tasks()->first()->update(['body' => 'test again']);
        $project->tasks()->first()->update(['body' => 'test again again']);
        $this->assertDatabaseCount('activities', 4);
    }

    // @test
    public function testCompleteTask()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $project->addTask('first time');
        $project->tasks()->first()->update([
            'status' => 1,
        ]);
        $this->assertDatabaseHas('activities', ['description' => 'task completed'])->assertDatabaseCount('activities', 3);
    }

    // @test
    public function testIncompleteTask()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $project->addTask('first time');
        $project->tasks()->first()->update([
            'status' => 1,
        ]);
        $project->tasks()->first()->update([
            'status' => 0,
        ]);
        $this->assertDatabaseHas('activities', ['description' => 'task marked as in completed']);
    }

    // @test
    public function testActivityBeforeAndAfterCreateCreate()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $oldTitle = $project->title;
        $oldDesc = $project->description;
        $newTitle = 'new title';
        $newDesc = 'new desc';
        $project->update(['title' => $newTitle, 'description' => $newDesc]);
        $data = [
            'before' => ['title' => $oldTitle, 'description' => $oldDesc],
            'after' => ['title' => $newTitle, 'description' => $newDesc],
        ];
        $data = json_encode($data);
        $this->assertDatabaseHas('activities', ['data' => $data]);
    }

    // @test
    public function testActivityBeforeAndAfterTaskCreate()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $project->addTask('new task');
        $project->tasks()->first()->update(['body' => 'edited task']);
        $data = [
            'before' => ['body' => 'new task'],
            'after' => ['body' => 'edited task'],
        ];
        $data = json_encode($data);
        $this->assertDatabaseHas('activities', ['data' => $data]);
    }
}