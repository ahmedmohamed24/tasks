<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    // @test
    public function testUserCanHasProjects()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    // @test
    public function testProjectCanInviteOthers()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        $project = Project::factory()->create();
        $otherUser = User::factory()->create();
        $project->invite($otherUser);
        $this->assertTrue($project->members->contains($otherUser));
    }
}