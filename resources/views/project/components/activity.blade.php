<div class="my-card tw-mt-3">
    @foreach ($project->activity as $activity)
    <p class="tw-border-l-2 tw--ml-4 tw-pl-4 tw-border-blue-500  tw-text-sm {{$loop->last ? '': 'tw-my-2'}}">
        @include("project.activity.$activity->description") - <span class="tw-text-gray-400">{{$activity->created_at->diffForHumans()}}</span>
    </p>
    @endforeach
</div>
