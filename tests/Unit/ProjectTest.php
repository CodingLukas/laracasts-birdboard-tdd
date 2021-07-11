<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_has_a_path()
    {
        $project = Project::factory()->create();

        $this->assertEquals('/projects/' . $project->id, $project->path());
    }

    /** @test */
    public function it_belongs_to_an_owner()
    {
        $project = Project::factory()->create();

        $this->assertInstanceOf(User::class, $project->owner);
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $project = Project::factory()->create();

        $task = $project->addTask(['body' => 'Test task']);

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    /** @test */
    function it_can_invite_a_user()
    {
        /** @var Project $project */
        $project = Project::factory()->create();

        /** @var User $user */
        $user = User::factory()->create();
        $project->invite($user);

        $this->assertTrue($project->members->contains($user));
    }
}
