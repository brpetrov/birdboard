@extends ('layouts.app')

@section('content')
<div class="tw-bg-white tw-shadow-sm tw-mt-32 tw-p-6 tw-mx-auto tw-w-1/2">

    <h1 class="tw-heading tw-text-xl tw-text-center tw-my-3 ">Create Project</h1>

<form method="POST" action="/projects">
    @csrf
    @include('project.components.form',[
    'project'=>new App\Models\Project,
    'buttonText'=>'Create Project'
    ])

</form>
</div>

@endsection
