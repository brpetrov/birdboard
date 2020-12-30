@extends('layouts.app')
@section('content')
<header class="lg:tw-flex tw-justify-between tw-items-center tw-mb-3 tw-py-3 tw-m-6 lg:tw-m-0">
    <p class="tw-text-gray-400 tw-font-bold hover:tw-no-underline tw-text-lg tw-mb-4 lg:tw-mb-0"><a class="tw-text-blue-500" href="/projects">My Projects</a> / {{$project->title}}</p>
    <a class="site-button" href="{{$project->path()}}/edit">Edit Project</a>
</header>

<main>
    <div class="lg:tw-flex tw--mx-3">
        <div class="tw-container tw-mx-auto lg:tw-w-3/4 tw-px-3">
            <div class="tw-mb-8">
            <h2 class="tw-text-gray-400 tw-font-bold hover:tw-no-underline tw-text-lg tw-mb-3 tw-ml-3">Tasks</h2>
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
                        <input id="body"
                            name="body"
                            type="text"
                            class="tw-w-full tw-h-7 inline tw-outline-none"
                            placeholder="Add a new task... and press *ENTER*">
                    </form>

                </div>


            </div>

            <h2 class="tw-text-gray-400 tw-font-bold hover:tw-no-underline tw-text-lg tw-mb-3 tw-ml-3">General Notes</h2>
            <form method="POST" action="{{$project->path()}}">
            @csrf
            @method('PATCH')
            <div class="tw-w-full tw-h-48">
                <textarea style="resize: none" name="notes" class="my-card tw-w-5/6 md:tw-w-full tw-h-40" placeholder="Project notes...">{{$project->notes}}</textarea>
            </div>
            @if ($errors->any())
            <div class="tw-field tw-my-4">
                @foreach ($errors->all() as $error)
                    <li class="tw-text-sm tw-text-red-500">{{$error}}</li>
                @endforeach
            </div>
            @endif
            <button type="submit" class="site-button">Save</button>
            </form>
        </div>
        <div class="lg:tw-w-1/4 tw-px-3">
            @include('project.components.card')
            @include('project.components.activity')
        </div>
    </div>
</main>

@endsection
