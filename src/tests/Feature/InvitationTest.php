<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Str;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class InvitationTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // @test
    public function testUserCanInviteOthers()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $otherUser = User::factory()->create();
        $this->post($project->path().'/invite', ['email' => $otherUser->email])->assertRedirect($project->path())->assertSessionHasNoErrors();
        $this->assertTrue($project->members->contains($otherUser));
        $this->signUserIn($otherUser);
        $this->get("/project/{$project->id}")->assertSee($project->title);
    }

    // @test
    public function testOnlyProjectOwnerCanInvite()
    {
        // $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $otherUser = User::factory()->create();
        $dummyUser = User::factory()->create();
        $this->signUserIn($otherUser);
        $this->post($project->path().'/invite', ['email' => $dummyUser->email])->assertStatus(403);
        $this->assertFalse($project->members->contains($otherUser));
        $this->get("/project/{$project->id}")->assertStatus(403); //unauthorized
    }

    // @test
    public function testUserCanNotInviteHimSelf()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $this->post($project->path().'/invite', ['email' => Auth::user()->email])->assertStatus(302)->assertSessionHasErrors();
        $this->assertFalse($project->members->contains(Auth::user()));
    }

    // @test
    public function testUserSeeTheInvitedProjects()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $anotherUser = User::factory()->create();
        $project = Project::factory()->create();
        $project->invite($anotherUser);
        $this->signUserIn($anotherUser);
        $this->get('/project')->assertSee(Str::limit($project->title, 25, '...'));
    }
}