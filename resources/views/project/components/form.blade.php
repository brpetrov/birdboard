

        <div class="tw-field tw-my-3">
            <label class="label tw-text-base" for="title">Title</label>
            <div class="control">
                <input type="text" value="{{$project->title}}" class="tw-input tw-w-full tw-border tw-border-gray-300 tw-p-2" name="title" placeholder="Title" required>
            </div>
            @error('title')
            <div class="tw-text-red-400 tw-text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="tw-field tw-my-3">
            <label class="label tw-text-base" for="description">Description</label>
            <div class="control">
                <textarea name="description" class="tw-input tw-w-full tw-border tw-p-2" rows="5" required>{{$project->description}}</textarea>
            </div>
            @error('description')
            <div class="tw-text-red-400 tw-text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="field tw-my-2">
            <div class="tw-grid tw-justify-items-center">
                <button type="submit" class="site-button is-link tw-mx-3 tw-my-1">{{$buttonText}}</button>
                <a class="tw-mx-3 tw-my-1 tw-text-base tw-font-bold tw-text-gray-500" href="{{$project->path()}}">Cancel</a>
            </div>
        </div>




