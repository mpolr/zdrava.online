@extends('layouts.upload')
@section('upload.content')
    <div class="w-2/4">
        <!-- Контент -->
        <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black">
            {{ __('Upload and sync workouts') }}
        </h2>
        @if ($message = Session::get('success'))
            <div id="alert-3" class="flex p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Info</span>
                <div class="ml-3 text-sm font-medium">
                    {{ $message }}
                </div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                <svg aria-hidden="true" class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                <span class="sr-only">Danger</span>
                <div>
                    <span class="font-medium">{{ __('Ensure that these requirements are met:') }}</span>
                    <ul class="mt-1.5 ml-4 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ __($error) }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        <form action="{{ route('upload.workout') }}" method="POST" class="p-2" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center justify-center w-full">
                <label for="workout" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                        <svg aria-hidden="true" class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">{{ __('Click to select file') }}</span> {{ __('or drag and drop') }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">FIT, GPX {{ __('or') }} TCX ({{ __('max.') }} 25 {{ __('megabyte') }})</p>
                    </div>
                    <input id="workout" name="workout[]" type="file" class="hidden" multiple />
                </label>
            </div>
            <p class="py-2">
                <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">{{ __('Submit') }}</button>
            </p>
            {{--            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">--}}
        </form>
    </div>
    <div class="w-1/4 lg:w-3/12 px-8">
    </div>
@endsection
