<?php

namespace Tests\Feature;

use App\Models\Project;
use Facades\Tests\Setup\MyProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityFeedTest extends TestCase
{
    use RefreshDatabase;

    public function test_creating_a_project_generates_records_activity()
    {
        $project = Project::factory()->create();

        $this->assertCount(1, $project->activity);

        $this->assertEquals('created', $project->activity->first()->description);
    }

    public function test_updating_a_project_generates_records_activity()
    {
        $project = Project::factory()->create();
        $project->update(['title' => 'changed']);

        $this->assertCount(2, $project->activity);
        $this->assertEquals('updated', $project->activity->last()->description);
    }

    public function test_creating_a_new_task_records_project_activity()
    {
        $project = Project::factory()->create();

        $project->addTask('some taks');
        $this->assertCount(2, $project->activity);
        $this->assertEquals('created_task', $project->activity->last()->description);
    }

    public function test_completing_a_task_records_project_activity()
    {
        $project = MyProjectFactory::withTasks(1)->create();


        $this->actingAs($project->owner)
            ->patch($project->tasks->first()->path(), [
                'body' => 'foobar',
                'completed' => true
            ]);
        $this->assertCount(3, $project->activity);
        $this->assertEquals('completed_task', $project->activity->last()->description);
    }
}
