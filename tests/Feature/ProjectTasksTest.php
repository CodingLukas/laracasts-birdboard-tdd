<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    public function a_project_can_have_tasks()
    {
        $project = Project::factory()->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', ['body' => 'Test task']);

        $this->get($project->path())
            ->assertSee('Test task');
    }

    /** @test */
    public function only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();

        /** @var Project $project */
        $project = Project::factory()->create();

        $this->post($project->path() . '/tasks', ['body' => 'Test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'Test task']);
    }

    /** @test */
    function only_the_owner_of_a_project_may_update_a_task()
    {
        $this->signIn();

        /** @var Project $project */
        $project = Project::factory()->withTasks(1)->create();
        /** @var Task $task */
        $task = $project->addTask(['body' => 'test task']);

        $this->patch($project->tasks[0]->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /** @test */
    function a_task_can_be_updated()
    {
        $project = Project::factory()->withTasks(1)->create();

        $attributes = [
            'body' => 'changed',
            'completed' => true
        ];

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), $attributes);

        $this->assertDatabaseHas('tasks', $attributes);
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $project = Project::factory()->create();

        $attributes = Task::factory()->raw(['body' => '']);

        $this->actingAs($project->owner)
            ->post($project->path() . '/tasks', $attributes)
            ->assertSessionHasErrors('body');
    }
}
