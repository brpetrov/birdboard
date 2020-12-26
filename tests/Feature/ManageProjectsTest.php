<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


    public function test_guests_cannot_create_projects()
    {
        // $this->withoutExceptionHandling();
        $attributes = Project::factory()->raw();
        $this->get('/projects/create', $attributes)->assertRedirect('login');
        $this->post('/projects', $attributes)->assertRedirect('login');
    }

    public function test_guests_cannot_view_projects()
    {
        $this->get('/projects')->assertRedirect('login');
    }

    public function test_guests_cannot_view_a_single_project()
    {
        $project = Project::factory()->create();
        $this->get($project->path())->assertRedirect('login');
    }

    public function test_a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();
        $this->signIn();
        // $this->signIn();
        $this->get('/projects/create')->assertStatus(200);
        $attributes = [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->sentence(4),
            'notes' => 'general notes here.'
        ];

        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $attributes);

        $this->get($project->path())
            ->assertSee($attributes['title'])
            ->assertSee($attributes['description'])
            ->assertSee($attributes['notes']);
    }

    public function test_a_user_can_update_project()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->patch($project->path(), [
            'notes' => 'Changed'
        ])->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', ['notes' => 'Changed']);

        // $this->assertRedirect($project->path());
    }

    public function test_a_user_can_view_their_project()
    {
        $this->signIn();
        $this->withoutExceptionHandling();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee(Str::limit($project->description, 100, '...'));
    }

    public function test_an_authenticated_user_cannot_view_projects_of_others()
    {
        $this->signIn();
        // $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $this->get($project->path())->assertStatus(403);
    }

    public function test_an_authenticated_user_cannot_update_projects_of_others()
    {
        $this->signIn();
        // $this->withoutExceptionHandling();
        $project = Project::factory()->create();
        $this->patch($project->path(), [])->assertStatus(403);
    }

    public function test_a_project_requires_a_title_and_description()
    {
        $this->signIn();
        $attributes = Project::factory()->raw(['title' => '', 'description' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors(['title', 'description']);
    }
}