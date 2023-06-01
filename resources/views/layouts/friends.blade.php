@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 py-12">
        <div class="flex">
            <div class="w-2/4 lg:w-3/12 px-4">
                <!-- Боковое меню -->
            </div>
            @yield('friends.content')
        </div>
    </main>
@endsection
