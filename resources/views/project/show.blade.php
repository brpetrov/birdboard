@extends('layouts.app')
@section('content')
<header class="lg:tw-flex tw-justify-between tw-items-center tw-mb-3 tw-py-3 tw-m-6 lg:tw-m-0">
    <p class="tw-text-gray-400 tw-font-bold hover:tw-no-underline tw-text-lg tw-mb-4 lg:tw-mb-0"><a class="tw-text-blue-500" href="/projects">My Projects</a> / {{$project->title}}</p>
    <a class="site-button" href="/projects/create">New Project</a>
</header>

<main>
    <div class="lg:tw-flex tw--mx-3">
        <div class="lg:tw-w-3/4 tw-px-3">
            <div class="tw-mb-8">
            <h2 class="tw-text-gray-400 tw-font-bold hover:tw-no-underline tw-text-lg tw-mb-3">Tasks</h2>
            {{-- tasks --}}
                @foreach ($project->tasks as $task)

                    <div class="my-card mb-3">
                        <form method="POST" action="{{$task->path()}}">
                            @method('PATCH')
                            @csrf
                            <div class="tw-flex">
                                <input  name="body" value="{{$task->body}}" class="tw-outline-none tw-w-full {{$task->completed ? 'tw-text-gray-300':''}}">
                                <input class="ml-3" name="completed" type="checkbox" onchange="this.form.submit()" {{$task->completed ? 'checked':''}}>
                            </div>

                        </form>

                    </div>
                @endforeach

                <div class="my-card tw-mb-3">
                    <form method="POST" action="{{$project->path().'/tasks'}}">
                        @csrf
                        <input id="body" name="body" type="text" class="tw-w-full tw-h-7 inline tw-outline-none"  placeholder="Add a new task...">
                    </form>

                </div>


            </div>

            <h2 class="tw-text-gray-400 tw-font-bold hover:tw-no-underline tw-text-lg tw-mb-3">General Notes</h2>
            <form method="POST" action="{{$project->path()}}">
            @csrf
            @method('PATCH')
            <textarea name="notes" class="my-card tw-w-full" style="min-height: 200px" placeholder="Project notes...">{{$project->notes}}</textarea>
            <button type="submit" class="site-button">Save</button>
            </form>
        </div>
        <div class="lg:tw-w-1/4 tw-px-3">
            @include('project.components.card')
        </div>
    </div>
</main>

@endsection
