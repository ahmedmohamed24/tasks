<?php
namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ProjectsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    // @test
    public function testOnlyAuthUsersCanCreateProject()
    {
        // $this->actingAs(User::factory()->create()); //to fail
        $project = Project::factory()->raw();
        $this->post('/project', $project)->assertRedirect('login');
    }

    // @test
    public function testUserCanCreateProject()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $params = Project::factory()->raw();
        //when a post request got
        $this->post('/project', $params)->assertStatus(302)->assertSessionHasNoErrors();
        //test the data is set into DB
        $this->assertDatabaseHas('projects', $params);
        //test redirect to projects page to see this post
        $this->get('/project')->assertSee(Str::limit($params['title'], 25, '...'));
    }

    // @test
    public function testUserCanAddNotesToProject()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $notes = ['notes' => 'hello this is a test note'];
        $this->patch($project->path().'/notes', $notes)->assertRedirect()->assertSessionHasNoErrors();
        // $project->update(['notes'=>'hello this is a test note']);
        $this->assertDatabaseHas('projects', $notes);
    }

    /** @test */
    public function testProjectTitleIsRequired()
    {
        $this->actingAs(User::factory()->create());
        $params = Project::factory()->raw(['title' => '']);

        $this->post('/project', $params)->assertSessionHasErrors('title');
    }

    // @test
    public function testProjectDescriptionIsRequired()
    {
        $this->actingAs(User::factory()->create());
        $params = Project::factory()->raw(['description' => '']);
        $this->post('/project', $params)->assertSessionHasErrors();
    }

    // @test
    public function testUserCannotViewOtherProjects()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1);
        $project = Project::factory()->create();
        $this->actingAs($user2);
        $this->get($project->path())->assertStatus(403);
    }

    // @test
    public function testUserCanView()
    {
        // $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->actingAs($user);
        $project = Project::factory()->create(['owner' => $user->id]);
        $this->get($project->path())->assertStatus(200);
    }

    // @test
    public function testIsUpdatedWhenTaskIsUpdated()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $task = $project->addTask('test');
        //begin update section
        $newTask = Task::factory()->raw(['project' => $project->id]);
        $this->patch($project->path().'/task/'."{$task->id}", $newTask);
        $this->assertEquals($project->updated_at, $task->updated_at);
    }

    // @test
    public function testUserCanUpdate()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $newData = ['title' => 'new title', 'description' => 'lorem inpsum', 'notes' => 'hello this is a test note'];
        $this->patch($project->path(), $newData)->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseHas('projects', $newData);
    }

    // @test
    public function testUserCanDelete()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $this->assertDatabaseHas('projects', $project->only(['title', 'description']));
        $this->delete("/project/{$project->id}", $project->toArray())->assertRedirect('/project');
        $this->assertDatabaseMissing('projects', $project->only(['title', 'description']));
    }

    // @test
    public function testProjectActivitiesIsDeletedAfterDeletingProject()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $this->assertInstanceOf(Activity::class, $project->activity()->first());
        $this->assertDatabaseCount('activities', 1);
        $this->assertNotNull($project->activity);
        $this->delete("/project/{$project->id}", $project->toArray());
        $this->assertDatabaseCount('activities', 0);
        $this->assertNotInstanceOf(Activity::class, $project->activity()->first());
    }

    public function testProjectTasksDeletedAfterProjectDeleting()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $project->addTask('hello');
        $this->assertDatabaseCount('activities', 2);
        $this->delete("/project/{$project->id}", $project->toArray());
        $this->assertDatabaseCount('activities', 0);
    }

    // @test
    public function testUserCanNotDeleteOthersProjects()
    {
        // $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $this->signUserIn();
        $this->delete("/project/{$project->id}", $project->toArray())->assertStatus(403);
        $this->assertDatabaseHas('projects', $project->only(['title', 'description']));
    }
}
