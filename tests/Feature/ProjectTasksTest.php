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
        $this->signIn();

        $projectData = Project::factory()->raw();
        /** @var Project $project */
        $project = auth()->user()->projects()->create($projectData);

        $this->post($project->path() . '/tasks', ['body' => 'Test task']);

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
        $project = Project::factory()->create();
        /** @var Task $task */
        $task = $project->addTask(['body' => 'test task']);

        $this->patch($task->path(), ['body' => 'changed'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    /** @test */
    function a_task_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $projectData = Project::factory()->raw();
        /** @var Project $project */
        $project = auth()->user()->projects()->create($projectData);

        /** @var Task $task */
        $task = $project->addTask(['body' => 'Test task']);

        $this->patch(
            $project->path() . '/tasks/' . $task->id,
            [
                'body' => 'changed',
                'completed' => true
            ]
        );

        $this->assertDatabaseHas(
            'tasks',
            [
                'body' => 'changed',
                'completed' => true
            ]
        );
    }

    /** @test */
    public function a_task_requires_a_body()
    {
        $this->signIn();

        $projectData = Project::factory()->raw();
        /** @var Project $project */
        $project = auth()->user()->projects()->create($projectData);

        $attributes = Task::factory()->raw(['body' => '']);

        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors('body');
    }
}
