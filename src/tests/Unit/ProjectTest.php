<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use WithFaker,RefreshDatabase;
    /**@test*/
    public function test_project_has_path()
    {
        $project=Project::factory()->create();
        $this->assertEquals($project->path(), "/project/$project->id");
    }
    /**@test*/
    public function test_project_belongs_to_user()
    {
        $project=Project::factory()->create();
        $this->assertInstanceOf(User::class, $project->user);
    }
}