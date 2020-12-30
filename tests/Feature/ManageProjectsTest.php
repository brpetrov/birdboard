<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Database\Factories\ProjectFactory;
use Facades\Tests\Setup\MyProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    // public function test_guests_cannot_manage_projects()
    // {
    //     $project = Project::factory()->create();
    //     $this->get('/projects')->assertRedirect('login');
    //     $this->get('/projects/create',)->assertRedirect('login');
    //     $this->get($project->path() . '/edit')->assertRedirect('login');
    //     $this->get($project->path())->assertRedirect('login');
    //     $this->post('/projects', $project->toArray())->assertRedirect('login');
    // }


    public function test_guests_cannot_create_projects()
    {
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


    public function test_guests_cannot_delete_projects()
    {
        $project = MyProjectFactory::create();

        $this->delete($project->path())
            ->assertRedirect('/login');

        $this->signIn();
        $this->delete($project->path())
            ->assertStatus(403);
    }

    public function test_a_user_can_create_a_project()
    {
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


    public function test_a_user_can_delete_a_project()
    {
        $project = MyProjectFactory::create();

        $this->actingAs($project->owner)
            ->delete($project->path())
            ->assertRedirect('/projects');

        $this->assertDatabaseMissing('projects', $project->only('id'));
    }


    public function test_a_user_can_update_a_project()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->patch($project->path(), $attributes = [
            'title' => 'Changed', 'description' => 'Changed', 'notes' => 'Changed'
        ])->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertStatus(200);

        $this->assertDatabaseHas('projects', $attributes);

        // $this->assertRedirect($project->path());
    }


    public function test_a_user_can_update_a_projects_general_notes()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);

        $this->patch($project->path(), $attributes = ['notes' => 'Changed'])
            ->assertRedirect($project->path());

        $this->get($project->path() . '/edit')->assertStatus(200);

        $this->assertDatabaseHas('projects', $attributes);
    }


    public function test_a_user_can_view_their_project()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->get($project->path())
            ->assertSee($project->title)
            ->assertSee(Str::limit($project->description, 100, '...'));
    }


    public function test_an_authenticated_user_cannot_view_projects_of_others()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->get($project->path())->assertStatus(403);
    }

    public function test_an_authenticated_user_cannot_update_projects_of_others()
    {
        $this->signIn();
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
