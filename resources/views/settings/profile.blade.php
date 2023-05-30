@extends('layouts.settings')
@section('settings.content')
    <div class="w-2/4">
        <!-- Контент -->
        <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black">
            {{ __('My profile') }}
        </h2>
        @livewire('upload.photo')
    </div>
    <div class="w-1/4 lg:w-3/12 px-8">
        <h3 class="text-3xl font-bold dark:text-white">
            {{ __('My account') }}
        </h3>
        <p class="pt-4">
            Почта:<br/>
            {{ Auth::user()->email }}
        </p>
        <p class="pt-4">
            Регистрация:<br/>
            {{ Auth::user()->created_at }}
        </p>
    </div>
@endsection
