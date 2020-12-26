<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;

class ProjectTaskController extends Controller
{
    public function store(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }
        request()->validate(['body' => 'required']);

        // fancy way from tutorial (check the project Model for more info about addTask)
        $project->addTask(request('body'));

        // NORMAL (noob) WAY with REQUEST $request as parameters in method
        // $request->validate(['body' => 'required']);

        // $project->tasks()->create([
        //     'body' => $request->body
        // ]);
        return redirect($project->path());
    }

    public function update(Project $project, Task $task)
    {
        if (auth()->user()->isNot($task->project->owner)) {
            abort(403);
        }

        request()->validate([
            'body' => 'required'
        ]);

        $task->update([
            'body' => request('body'),
            'completed' => request()->has('completed')
        ]);
        return redirect($project->path());
    }
}
