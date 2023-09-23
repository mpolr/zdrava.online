<main class="container mx-auto px-0 py-12 max-w-screen-lg">
    <div class="w-full">
        <button wire:click="save" type="button" class="object-right px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">{{ __('Save') }}</button>
        <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
    </div>

    <div class="grid grid-cols-3 gap-4">
        @error('activity') @livewire('toast.errors') @enderror
        @if (Session::get('success'))
            @livewire('toast.success')
        @endif
        <div class="w-full col-span-2">
            <div class="mb-6">
                <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Name') }}</label>
                <input wire:model="activity.name" type="text" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="" required>
            </div>
            <div class="mb-6 sm:col-span-2">
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Description') }}</label>
                <textarea wire:model="activity.description" id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="{{ __('Tell, how the training went') }}"></textarea>
            </div>
            <div class="mb-6 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                <div class="col-span-full">
                    <label for="media" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Media') }}</label>
                    <label for="media" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">{{ __('Click to select file') }}</span> {{ __('or drag and drop') }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG {{ __('or') }} GIF ({{ __('max.') }} 10Mb)</p>
                        </div>
                        <input wire:model="media" wire:change.debounce="save(true)" id="media" type="file" class="hidden" />
                    </label>
                </div>
            </div>
        </div>
        <div class="w-fit">
            <div class="mb-6 w-full">
                <img class="h-auto max-w rounded-lg" src="{{ $activity->getImage() }}" alt="" />
            </div>
            <blockquote class="p-4 my-4 border-l-4 border-gray-300 bg-gray-50 dark:border-gray-500 dark:bg-gray-800">
                <span class="text-gray-900 dark:text-white">{{ __('Date') }}: {{ $activity->created_at }}</span><br>
                <span class="text-gray-900 dark:text-white">{{ __('Distance') }}: {{ __(':distance km', ['distance' => $activity->getDistance()]) }}</span><br>
                <span class="text-gray-900 dark:text-white">{{ __('Duration') }}: {{ $activity->getDuration() }}</span><br>
                <span class="text-gray-900 dark:text-white">{{ __('Elevation') }}: {{ __(':elevation m', ['elevation' => $activity->getElevationGain()]) }}</span>
            </blockquote>
        </div>
    </div>
</main>
