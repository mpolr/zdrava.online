@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 py-12">
        <div class="flex flex-wrap">
            <div class="w-full lg:w-3/12 px-4">
                <!-- Боковое меню -->
                <div class="max-w-xs flex flex-col rounded-md shadow-sm">
                    <a href="{{ route('upload.workout') }}" type="button" class="py-3 px-4 inline-flex justify-left items-center gap-2 rounded-t-md border font-medium bg-white text-gray-700 align-middle hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all text-sm dark:bg-gray-800 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400">
                        {{ __('File') }}
                    </a>
                    <a href="{{ route('upload.workout') }}" type="button" class="-mt-px py-3 px-4 inline-flex justify-left items-center gap-2 border font-medium bg-white text-gray-700 align-middle hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all text-sm dark:bg-gray-800 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400">
                        {{ __('Manually add') }}
                    </a>
                    <a href="#" type="button" class="-mt-px py-3 px-4 inline-flex justify-left items-center gap-2 border font-medium bg-white text-gray-700 align-middle hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all text-sm dark:bg-gray-800 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400">
                        {{ __('Import from Strava') }}
                    </a>
                </div>
            </div>
            @yield('upload.content')
        </div>
    </main>
@endsection
