@extends('layouts.site')
@section('content')
    <div class="px-6 py-12 md:px-12 bg-gray-50 text-gray-800 text-center lg:text-left">
        <div class="container mx-auto xl:px-32">
            <div class="grid lg:grid-cols-2 gap-12 flex items-center">
                <div class="mt-12 lg:mt-0">
                    <h1 class="text-5xl md:text-6xl xl:text-7xl font-bold tracking-tight mb-12">
                        Try Zdrava.<br />
                        <span class="text-blue-600">Invite friends.</span>
                    </h1>
                    <p class="text-gray-600">
                        Register today and start tracking your progress.<br />
                        Discover new peoples and places.
                    </p>
                </div>
                <div class="mb-12 lg:mb-0">
                    <div class="block rounded-lg shadow-lg bg-white px-6 py-12 md:px-12">
                        <form method="POST" action="{{ route('auth.register.post') }}">
                            @csrf
                            <div class="grid md:grid-cols-2 md:gap-6">
                                <div class="mb-6">
                                    <input name="first_name" value="{{ old('first_name') }}" type="text" class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('First name') }}" required/>
                                </div>
                                <div class="mb-6">
                                    <input name="last_name" value="{{ old('last_name') }}" type="text" class="form-control block w-full px-3 py-1.5 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('Last name') }}" required/>
                                </div>
                            </div>
                            <input name="email" value="{{ old('email') }}" type="email" class="form-control block w-full px-3 py-1.5 mb-6 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('Email address') }}" required/>
                            <input name="password" type="password" autocomplete="password" class="form-control block w-full px-3 py-1.5 mb-6 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('Password') }}" required/>
                            <input name="password_confirmation" type="password" class="form-control block w-full px-3 py-1.5 mb-6 text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" placeholder="{{ __('Password confirmation') }}" required/>
                            <div class="form-check flex justify-center mb-6">
                                <input name="subscribe_news" value="1" class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer" type="checkbox" id="flexCheckChecked" checked>
                                <label class="form-check-label inline-block text-gray-800" for="flexCheckChecked">
                                    {{ __('Subscribe to our newsletter') }}
                                </label>
                            </div>
                            <button type="submit" data-mdb-ripple="true" data-mdb-ripple-color="light" class="inline-block px-6 py-2.5 mb-6 w-full bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
                                {{ __('Sign up') }}
                            </button>
                            <!-- Validation Errors -->
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
                            <div class="text-center">
                                <p class="mb-6">{{ __('or sign up with:') }}</p>
                            </div>
                            <div class="flex justify-center">
                                <a href="#" role="button" class="text-blue-600 hover:text-blue-700 focus:text-blue-700 action:text-blue-800 transition duration-200 ease-in-out">
                                    <!-- Google -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512" class="w-4 h-4 mx-4"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="currentColor" d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/></svg>
                                </a>
                                <a href="#" role="button" class="text-blue-600 hover:text-blue-700 focus:text-blue-700 action:text-blue-800 transition duration-200 ease-in-out">
                                    <!-- Facebook -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-4 h-4 mx-4"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
