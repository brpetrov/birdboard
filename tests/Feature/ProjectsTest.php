<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    public function test_user_can_view_a_project()
    {
        $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    public function test_a_project_requires_a_title_and_description()
    {
        $attributes = Project::factory()->raw(['title' => '', 'description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors(['title', 'description']);
    }

    public function test_a_project_requires_an_owner()
    {
        $this->withoutExceptionHandling();
        $attributes = Project::factory()->raw();
        $this->post('/projects', $attributes)->assertRedirect('login');
    }
}
