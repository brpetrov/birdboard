<div class="my-card tw-h-52">
    <h3 class="tw-font-normal tw-text-xl tw-pb-4 tw-pt-2 mb-3 tw-mb-6 tw--ml-5 tw-border-l-4 tw-border-blue-500 tw-pl-4"><a href="{{$project->path()}}">{{$project->title}}</a></h3>
    <div class="tw-text-gray-400">{{Str::limit($project->description,150)}}</div>
</div>
