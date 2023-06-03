@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 py-12">
        <div class="flex">
            <div class="w-1/4">

            </div>
            <div class="w-3/4 ml-4">
                @livewire('friends.requests')
            </div>
        </div>
    </main>
@endsection
