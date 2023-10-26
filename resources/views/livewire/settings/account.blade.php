@section('title', __('My account') . ' | Zdrava')
<div class="container mx-auto px-0 py-12">
    <div class="flex sm:flex-row md:flex-row flex-col gap-4">
        <div>
            @include('components.settings-menu')
        </div>
        <div class="w-full">
            <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black dark:text-gray-100">
                {{ __('My account') }}
            </h2>
            <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
            @error('profile') @livewire('toast.errors') @enderror
            @if (session()->get('success'))
                @livewire('toast.success')
            @endif
            <div>
                <label for="locale" class="dark:text-gray-100">{{ __('Interface language') }}</label>
                <select wire:model="locale" name="locale" id="locale" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                    @foreach(config('app.available_locales') as $locale_name => $available_locale)
                        @if($available_locale === $locale)
                            <option value="{{ $available_locale }}" selected>{{ $locale_name }}</option>
                        @else
                            <option value="{{ $available_locale }}">{{ $locale_name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="mt-8">
                <label for="theme" class="dark:text-gray-100">{{ __('Interface theme') }}</label>
                <select wire:select.prevent="save" wire:model="theme" name="theme" id="theme" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                    <option value="" @if(session()->get('theme') != 'dark') selected @endif>{{ __('Bright') }}</option>
                    <option value="dark" @if(session()->get('theme') == 'dark') selected @endif>{{ __('Dark') }}</option>
                </select>
            </div>
            <p class="pt-4">
                <button wire:click="save" type="button" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-full">{{ __('Save') }}</button>
            </p>
            <div class="mt-16">
                <a href="{{ route('settings.account.delete') }}">
                    <button type="button" class="px-4 py-2 bg-red-500 hover:bg-red-700 text-white font-bold rounded-full">{{ __('Account deleting') }}</button>
                </a>
            </div>
        </div>
    </div>
</div>
