@extends('layouts.site')
@section('content')
<main class="container mx-auto px-0 py-12">
    <div class="flex">
        <div class="w-1/4">
            <div class="flex items-center justify-center w-full">
                @if ($user->photo)
                    <img src="{{ $user->getPhoto() }}"
                         alt="{{ $user->getFullName() }}"
                         loading="lazy"
                         class="w-32 h-32 mb-3 p-1 rounded-full ring-2 ring-gray-300 dark:ring-gray-500" />
                @else
                    <div class="relative inline-flex items-center justify-center w-24 h-24 mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                        <span class="font-bold text-3xl text-gray-600 dark:text-gray-300">
                            {{ $user->getInitials() }}
                        </span>
                    </div>
                @endif
                <h2 class="mb-2 mt-0 ml-4 text-4xl font-medium leading-tight text-black">
                    {{ $user->getFullName() }}
                </h2>
            </div>
            <hr class="h-px mt-4 mb-8 bg-gray-200 border-0 dark:bg-gray-700">
            <div class="flex items-center w-full">
                <div class="relative inline-flex items-center justify-center w-24 h-24 mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                    <span class="font-bold text-3xl text-gray-600 dark:text-gray-300">
                        {{ $user->getInitials() }}
                    </span>
                </div>
                <div class="relative inline-flex items-center justify-center w-24 h-24 mb-3 overflow-hidden bg-gray-300 rounded-full dark:bg-gray-600">
                    <span class="font-bold text-3xl text-gray-600 dark:text-gray-300">
                        {{ $user->getInitials() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="w-4/4 ml-4">
            <div>
                @error('profile') @livewire('toast.errors') @enderror
                @if (Session::get('success'))
                    @livewire('toast.success')
                @endif
            </div>
        </div>
        <div class="w-1/4">

        </div>
    </div>
</main>
@endsection
