<div>
    Аватар:
    @error('photo') @livewire('toast.errors') @enderror
    @if (Session::get('success'))
        @livewire('toast.success')
    @endif
    <div class="flex items-center justify-center w-full">
        <label for="photo" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                @if (Auth::user()->photo)
                    <img src="{{ Auth::user()->getPhoto() }}"
                         alt="{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}"
                         loading="lazy"
                         class="w-32 h-32 mb-3 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" />
                @else
                    <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                @endif
                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">{{ __('Click to select file') }}</span> {{ __('or drag and drop') }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG {{ __('or') }} GIF ({{ __('max.') }} 512x512px)</p>
            </div>
            <input wire:model="photo" wire:change.debounce="save" id="photo" type="file" class="hidden" />
        </label>
    </div>
</div>
