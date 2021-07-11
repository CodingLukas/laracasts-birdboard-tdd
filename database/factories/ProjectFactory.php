<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'description' => $this->faker->sentence(1),
            'notes' => 'Foobar notes',
            'owner_id' => User::factory()
        ];
    }

    public function withTasks($times = 0): Factory
    {
        return $this->afterCreating(
            function (Project $project) use ($times) {
                Task::factory()->count($times)->create(['project_id' => $project->id]);
            }
        );
    }

    public function withOwner(User $user): Factory
    {
        return $this->afterMaking(
            function (Project $project) use ($user) {
                $project->owner_id = $user->id;
            }
        );
    }
}
