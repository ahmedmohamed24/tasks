<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase,WithFaker;
    /**@test*/
    public function test_can_view_activities()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        Project::factory()->create();
        $this->get('/activity')->assertStatus(200)->assertSee("new project created");
    }
    
    /**@test*/
    public function test_can_delete_activity()
    {
        $this->withoutExceptionHandling();
        $this->signUserIn();
        Project::factory()->create();
        $activity=auth()->user()->activities()->first();
        $this->delete("/activity/$activity->id")->assertStatus(302)->assertDontSee("new project created")->assertSessionHas('msg');
        $this->assertDatabaseMissing('activities', ['description'=>$activity->description]);
    }

    /**@test*/
    public function test_only_owner_may_delete_activity()
    {
        // $this->withoutExceptionHandling();
        $this->signUserIn();
        Project::factory()->create();
        $activity=auth()->user()->activities()->first();
        $this->signUserIn();
        $this->delete("/activity/$activity->id")->assertStatus(403);
    }
}