@extends('layouts.site')
@section('title', __('Forgot your password?') . ' | Zdrava')
@section('description', 'Запрос восстановления пароля на сайте Zdrava')
@section('content')
    <div class="px-6 py-12 md:px-12 text-gray-800 text-center lg:text-left">
        <div class="container mx-auto xl:px-32">
            <div class="grid lg:grid-cols-2 gap-12 flex items-center">
                <div class="mt-12 lg:mt-0">
                    <h1 class="text-5xl md:text-6xl xl:text-7xl font-bold tracking-tight mb-12">
                        {{ __('Password Reset') }}
                    </h1>
                </div>
                <div class="mb-12 lg:mb-0">
                    <div class="block rounded-lg shadow-lg bg-white px-6 py-12 md:px-12">
                        <form method="POST" action="{{ route('auth.password.email') }}">
                            @csrf
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Email address') }}</label>
                            <input name="email" id="email" type="email" class="form-control block w-full px-3 py-1.5 mb-6 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('Email address') }}" required autofocus/>
                            @if (session()->has('message'))
                                <div id="alert-success" class="flex p-4 mb-4 mt-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400 border border-green-300 dark:border-green-800" role="alert">
                                    <svg aria-hidden="true" class="flex-shrink-0 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                    <span class="sr-only">Info</span>
                                    <div class="ml-3 text-sm font-medium">
                                        {{ session()->get('message') }}
                                    </div>
                                </div>
                            @endif
                            <button type="submit" data-mdb-ripple="true" data-mdb-ripple-color="light" class="inline-block px-6 py-2.5 mb-6 w-full bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
                                {{ __('Send Password Reset Link') }}
                            </button>
                            @if ($errors->any())
                                <div class="mb-4 mt-4">
                                    <div class="font-medium text-red-600">
                                        {{ __('Whoops! Something went wrong.') }}
                                    </div>
                                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
