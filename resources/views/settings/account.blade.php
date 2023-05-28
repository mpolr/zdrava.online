@extends('layouts.settings')
@section('settings.content')
    <div class="w-2/4">
        <!-- Контент -->
        <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black">
            {{ __('My account') }}
        </h2>
        <p>
            <form action="{{ route('settings.account.set.locale') }}" method="POST" class="p-2">
            @csrf
                <label for="site_locale">{{ __('Interface language') }}</label>
                <select id="site_locale" name="locale" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                    @foreach(config('app.available_locales') as $locale_name => $available_locale)
                        @if($available_locale === app()->getLocale())
                            <option value="{{ $available_locale }}" selected>{{ $locale_name }}</option>
                        @else
                            <option value="{{ $available_locale }}">{{ $locale_name }}</option>
                        @endif
                    @endforeach
                </select>
                <p class="py-2">
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">{{ __('Save') }}</button>
                </p>
            </form>
        </p>
    </div>
    <div class="w-1/4 lg:w-3/12 px-4">
        <p>
            Всякое-разное тут
        </p>
    </div>
@endsection
