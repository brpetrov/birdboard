@extends('layouts.app')
@section('content')

<header class="tw-flex tw-justify-between tw-items-center tw-mb-3 tw-py-3 tw-m-6 lg:tw-m-0">
    <h2 class="tw-text-gray-400 tw-font-bold hover:tw-no-underline hover:tw-text-gray-500 tw-text-lg">My Projects</h2>
    <a class="site-button" href="/projects/create" >Add Project</a>
</header>
<main class="lg:tw-grid lg:tw-grid-cols-3 lg:tw-gap-5">
    @forelse ($projects as $project)
   @include('project.components.card')
    @empty
    <div>No projects yet.</div>
    @endforelse
</main>
@endsection
