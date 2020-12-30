<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ProjectController extends Controller
{

    public function index()
    {
        // dd(auth()->user()->projects());
        $projects = auth()->user()->projects()->get();
        return view('project.index', [
            'projects' => $projects,
        ]);
    }


    public function create()
    {
        return view('project.create');
    }


    public function store(Request $request)
    {
        $attributes = request()->validate([
            'title' => 'required',
            'description' => 'required|max:100|min:5',
            'notes' => 'nullable'
        ]);

        $attributes['owner_id'] = auth()->id();
        $project = auth()->user()->projects()->create($attributes);
        return redirect($project->path());
    }


    public function show(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }
        return view('project.show', ['project' => $project]);
    }


    public function edit(Project $project)
    {
        return view('project.edit', ['project' => $project]);
    }

    public function update(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }

        // Same as above
        // if (auth()->user() == !$project->owner) {
        //     abort(403);
        // }

        $attributes = request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required|max:100',
            'notes' => 'nullable'
        ]);
        $project->update($attributes);

        return redirect($project->path());
    }

    public function destroy(Project $project)
    {
        if (auth()->user()->isNot($project->owner)) {
            abort(403);
        }
        $project->delete();
        return redirect('/projects');
    }
}
