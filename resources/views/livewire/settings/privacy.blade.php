<div>
    <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black">
        {{ __('Privacy') }}
    </h2>
    <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
    @error('privacy') @livewire('toast.errors') @enderror
    @if (Session::get('success'))
        @livewire('toast.success')
    @endif
    <div>
        <label class="relative inline-flex items-center cursor-pointer">
            <input wire:model="private" type="checkbox" value="" class="sr-only peer">
            <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
            <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Private profile') }}</span>
        </label>
    </div>
</div>
