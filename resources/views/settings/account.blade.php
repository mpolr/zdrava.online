@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 py-12">
        <div class="flex">
            <div class="w-1/4">
                @include('settings._menu')
            </div>
            <div class="w-3/4 ml-4">
                @livewire('settings.account')
            </div>
        </div>
    </main>
@endsection
