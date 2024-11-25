@extends('layouts.site')
@section('title', __('Reset Password') . ' | Zdrava')
@section('description', 'Смена пароля на сайте Zdrava')
@section('content')
    <div class="px-6 py-12 md:px-12 text-gray-800 text-center lg:text-left">
        <div class="container mx-auto xl:px-32">
            <div class="grid lg:grid-cols-2 gap-12 items-start">
                <div class="mt-12 lg:mt-0">
                    <h1 class="text-5xl md:text-6xl xl:text-7xl font-bold tracking-tight mb-12 dark:text-white">
                        {{ __('Password Reset') }}
                    </h1>
                </div>
                <div class="mb-12 lg:mb-0">
                    <div class="block rounded-lg shadow-lg bg-white px-6 py-12 md:px-12">
                        <form method="POST" action="{{ route('auth.password.reset') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Email address') }}</label>
                            <input name="email" id="email" type="email" class="form-control block w-full px-3 py-1.5 mb-6 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('Email address') }}" required autofocus/>
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif

                            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Password') }}</label>
                            <input name="password" id="password" type="password" class="form-control block w-full px-3 py-1.5 mb-6 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('Password') }}" required/>
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif

                            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ __('Confirm Password') }}</label>
                            <input name="password_confirmation" id="password_confirmation" type="password" class="form-control block w-full px-3 py-1.5 mb-6 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('Confirm Password') }}" required/>
                            @if ($errors->has('password_confirmation'))
                                <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                            @endif

                            <button type="submit" data-mdb-ripple="true" data-mdb-ripple-color="light" class="inline-block px-6 py-2.5 mb-6 w-full bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
                                {{ __('Reset Password') }}
                            </button>
                            @if ($errors->any())
                                <div class="mb-4">
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
