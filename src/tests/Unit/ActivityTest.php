<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ActivityTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    // @test
    public function testUserMayHaveOtherMembersActivities()
    {
        $this->withoutExceptionHandling();
        $owner = User::factory()->create();
        $this->signUserIn($owner);
        $project = Project::factory()->create();
        $firstMember = User::factory()->create();
        $project->invite($firstMember);
        $this->signUserIn($firstMember);
        $project->addTask('test');
        $this->assertNotEmpty(auth()->user()->activities()->get()->toArray());
    }
}