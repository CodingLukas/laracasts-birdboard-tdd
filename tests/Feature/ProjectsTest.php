<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /** @test */
    public function guests_cannot_create_projects()
    {
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];

        $this->post('/projects', $attributes)->assertRedirect('/login');

//        $this->post('/projects', $attributes)->assertRedirect('/projects');
//
//        $this->assertDatabaseHas('projects', $attributes);
//
//        $this->get('/projects')->assertSee($attributes['title']);
    }

    /** @test */
    public function a_project_requires_a_title()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attributes = Project::factory()->raw(['title' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_project_requires_a_description()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }

    /** @test */
    public function a_user_can_view_their_project()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->withoutExceptionHandling();

        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /** @test */
    public function a_project_requires_an_owner()
    {
        $attributes = Project::factory()->raw();

        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function only_authenticated_users_can_create_project()
    {
        $attributes = Project::factory()->raw();

        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    /** @test */
    public function guests_may_not_view_projects()
    {
        $this->get('/projects')->assertRedirect('login');
    }

    /** @test */
    public function guests_cannot_view_a_single_projects()
    {
        $project = Project::factory()->create();

        $this->get($project->path())->assertRedirect('login');
    }

    /** @test */
    public function an_authenticated_user_cannot_view_the_projects_of_others()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create();

        $this->get($project->path())->assertStatus(403);
    }
}
