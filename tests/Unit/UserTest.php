<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function a_user_has_projects()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    function a_user_has_accessible_projects()
    {
        $john = $this->signIn();

        Project::factory()->withOwner($john)->create();

        $this->assertCount(1, $john->accessibleProjects());

        /** @var User $sally */
        $sally = User::factory()->create();
        $nick = User::factory()->create();

        $project = tap(Project::factory()->withOwner($sally)->create())->invite($nick);

        $this->assertCount(1, $john->accessibleProjects());

        $project->invite($john);

        $this->assertCount(2, $john->accessibleProjects());
    }
}
