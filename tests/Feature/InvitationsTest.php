<?php

namespace Tests\Feature;

use App\Http\Controllers\ProjectTasksController;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function non_owners_may_not_invite_users()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(Project::factory()->create()->path() . '/invitations')
            ->assertStatus(403);
    }

    /** @test */
    function a_project_owner_can_invite_a_user()
    {
        $project = Project::factory()->create();

        $userToInvite = User::factory()->create();

        $this->actingAs($project->owner)
            ->post(
                $project->path() . '/invitations',
                [
                    'email' => $userToInvite->email
                ]
            )
            ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($userToInvite));
    }

    /** @test */
    function the_email_address_must_be_associated_with_a_valid_birdboard_account()
    {
        $project = Project::factory()->create();

        $this->actingAs($project->owner)
            ->post(
                $project->path() . '/invitations',
                [
                    'email' => 'notauser@example.com'
                ]
            )
            ->assertSessionHasErrors(
                [
                    'email' => 'The user you are inviting must have a Birdboard account.'
                ]
            );
    }

    /** @test */
    function invited_users_may_update_project_details()
    {
        $project = Project::factory()->create();

        /** @var User $newUser */
        $newUser = User::factory()->create();
        $project->invite($newUser);

        $taskData = ['body' => 'Foo task'];
        $this
            ->actingAs($newUser)
            ->post(action([ProjectTasksController::class, 'store'], $project), $taskData);
        $this->assertDatabaseHas('tasks', $taskData);
    }
}
