<div>
    <label for="locale">{{ __('Interface language') }}</label>
    <select wire:model="locale" name="locale" id="locale" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
        @foreach(config('app.available_locales') as $locale_name => $available_locale)
            @if($available_locale === $locale)
                <option value="{{ $available_locale }}" selected>{{ $locale_name }}</option>
            @else
                <option value="{{ $available_locale }}">{{ $locale_name }}</option>
            @endif
        @endforeach
    </select>
    <p class="pt-4">
        <button wire:click="save" type="button" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">{{ __('Save') }}</button>
    </p>
</div>
