@extends('layouts.site')
@section('title', __('Zdrava android app') . ' | Zdrava')
@section('content')
    <div class="container my-12 px-6 mx-auto">
        <p>
            <a href="{{ route('app.download', $versions->first()->version) }}" type="button" class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light">
                {{ __('Download Zdrava :version for android', ['version' => $versions->first()->version]) }}
            </a>
        </p>
        <br/>
        <p class="text-gray-500 dark:text-gray-400">
            Скачиваний: {{ $versions->first()->downloads }}
        </p>
        <br>
        <h1 class="text-4xl md:text-5xl xl:text-6xl font-bold tracking-tight mb-12">
            {{ __('Version history') }}
        </h1>
        <ol class="border-l-2 border-primary dark:border-primary-500">
            @foreach($versions as $version)
            <li>
                <div class="flex-start flex items-center">
                    <div
                        class="-ml-[9px] -mt-2 mr-3 flex h-4 w-4 items-center justify-center rounded-full bg-primary dark:bg-primary-500"></div>
                    <h4 class="-mt-2 text-xl font-semibold">v{{ $version->version }}</h4>
                </div>
                <div class="mb-6 ml-6 pb-6">
                    <span class="text-sm text-primary transition duration-150 ease-in-out hover:text-primary-600 focus:text-primary-600 active:text-primary-700 dark:text-primary-400 dark:hover:text-primary-500 dark:focus:text-primary-500 dark:active:text-primary-600">
                        {{ \Carbon\Carbon::parse($version->created_at)->translatedFormat('d.m.Y, l') }}
                    </span>
                    <p class="mb-4 mt-2 text-neutral-600 dark:text-neutral-300">
                        {!! $version->description !!}
                    </p>
                </div>
            </li>
           @endforeach
        </ol>
    </div>
@endsection
