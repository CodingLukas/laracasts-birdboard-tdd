<?php

namespace Tests\Feature;

use App\Http\Controllers\ProjectTasksController;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function a_project_can_invite_a_user()
    {
        /** @var Project $project */
        $project = Project::factory()->create();

        /** @var User $newUser */
        $newUser = User::factory()->create();
        $project->invite($newUser);

        $this
            ->actingAs($newUser)
            ->post(action([ProjectTasksController::class, 'store'], $project), $task = ['body' => 'Foo task']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
