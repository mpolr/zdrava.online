@extends('layouts.settings')
@section('settings.content')
    <div class="w-2/4">
        <!-- Контент -->
        <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black">
            {{ __('Privacy') }}
        </h2>
        <p>
            Тут будут настройки конфиденциальности
        </p>
    </div>
    <div class="w-1/4 lg:w-3/12 px-4">
        <p>
            Всякое-разное тут
        </p>
    </div>
@endsection
