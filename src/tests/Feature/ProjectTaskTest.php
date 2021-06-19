<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ProjectTaskTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    // @test
    public function testUserCanAddTask()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );
        $task = Task::factory()->raw();
        $this->post($project->path().'/task', $task)->assertStatus(302);
        // $this->get($project->path())->assertSee($task);
    }

    // @test
    public function testTaskBodyIsRequiredOnCreate()
    {
        // $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = auth()->user()->projects()->create(
            Project::factory()->raw()
        );
        $task = Task::factory()->raw(['body' => '', 'project' => $project->id]);
        $this->post($project->path().'/task', $task)->assertSessionHasErrors('body');
        // $this->post($project->path().'/task',$task)->assertSessionHasNoErrors();
    }

    // @test
    public function testOnlyProjectOwnerCanAddTaskToIt()
    {
        // $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $task = Task::factory()->raw();
        $this->signUserIn();
        $this->post($project->path().'/task', $task)->assertForbidden();
        $this->assertDatabaseMissing('tasks', $task);
    }

    // @test
    public function testTaskMustHaveAProject()
    {
        // $this->withoutExceptionHandling();
        $this->signUserIn();
        $this->post('/project/ahmed/task')->assertStatus(404);
    }

    // @test
    public function testUserCanUpdateATask()
    {
        // $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $task = $project->addTask('test');
        //begin update section
        $newTask = Task::factory()->raw(['project' => $project->id]);
        $this->patch($project->path().'/task/'."{$task->id}", $newTask)->assertStatus(302)->assertSessionHasNoErrors();
        $this->assertDatabaseHas('tasks', $newTask);
    }

    // @test
    public function testTaskBodyIsRequiredOnUpdate()
    {
        $this->signUserIn();
        $project = Project::factory()->create();
        $task = $project->addTask('test');
        //begin update section
        $newTask = ['body' => ''];
        $this->patch($task->path(), $newTask)->assertSessionHasErrors('body');
    }

    // @test
    public function testOnlyOwnerCanUpdateHisTask()
    {
        // $this->withoutExceptionHandling();
        $this->signUserIn();
        $firstUserProject = Project::factory()->create();
        $task = $firstUserProject->addTask('test');
        $this->signUserIn();
        //try to update after signing in with another account
        $newTask = Task::factory()->raw();
        $this->patch($task->path(), $newTask)->assertForbidden();
    }

    // @test completed or not
    public function testUserCanChangeTaskStatus()
    {
        // $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $task = $project->addTask('test');
        $newTask = Task::factory()->raw(['body' => 'hello', 'status' => 1, 'project' => $project->id]);
        $this->patch($task->path(), $newTask)->assertSessionHasNoErrors()->assertStatus(302);
        $this->assertDatabaseHas('tasks', $newTask);
    }

    // @test
    public function testEveryTaskHasAPath()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $task = $project->addTask('test');
        $this->assertNotNull($task->path());
    }

    // @test
    public function testEveryTaskMustHaveProject()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        // $task=Task::factory()->create(['project'=>null]);
        $task = Task::factory()->create();
        $this->assertInstanceOf(Project::class, $task->getProject);
    }
}