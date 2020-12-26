<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Facades\Tests\Setup\MyProjectFactory;
use Tests\TestCase;

class ProjectTasksTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_cannot_add_tasks_to_projects()
    {
        $project = Project::factory()->create();
        $this->post($project->path() . '/tasks')->assertRedirect('login');
    }

    public function test_only_the_owner_of_a_project_may_add_tasks()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $this->post($project->path() . '/tasks', ['body' => 'test task'])
            ->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'test task']);
    }

    public function test_a_project_can_have_tasks()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $this->post($project->path() . '/tasks', ['body' => 'test task']);

        $this->get($project->path())
            ->assertSee('test task');
    }

    public function test_a_task_can_be_updated()
    {

        // cool method created in tutorial. do not know the benefits of it other than it looks cleaner (I will never remember how it's made)
        $project = MyProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'changed',
                'completed' => true
            ]);

        //Coll method made from tutorial

        $this->assertDatabaseHas('tasks', [
            'body' => 'changed',
            'completed' => true
        ]);
    }

    public function test_only_the_owner_of_a_project_may_update_tasks()
    {
        $this->signIn();
        $project = Project::factory()->create();
        $task = $project->addTask('test task');
        $this->patch($task->path(), ['body' => 'changed'])
            ->assertStatus(403);
        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

    public function test_a_task_requires_a_body()
    {
        $this->signIn();
        $project = Project::factory()->create(['owner_id' => auth()->id()]);
        $attributes = Task::factory()->raw(['body' => '']);
        $this->post($project->path() . '/tasks', $attributes)->assertSessionHasErrors(['body']);
    }
}
