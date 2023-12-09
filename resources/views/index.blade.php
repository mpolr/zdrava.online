@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 py-12 max-w-screen-lg">
        <div class="grid grid-cols-1 gap-4">
            <div class="w-full">
                <div class="text-center text-gray-800 py-24 px-6">
                    <h1 class="text-5xl md:text-6xl xl:text-7xl font-bold tracking-tight mb-12">
                        <span class="text-blue-600">Zdrava</span><br />
                        {{ __('Social network for athletes') }}
                    </h1>
                    <a href="{{ route('app') }}" class="inline-block px-7 py-3 bg-transparent text-blue-600 font-medium text-sm leading-snug uppercase rounded hover:text-blue-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none focus:ring-0 active:bg-gray-200 transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light" role="button">
                        {{ __('Zdrava android app') }}
                    </a>
                    <a href="https://t.me/zdrava_online" target="_blank" class="inline-block px-7 py-3 bg-transparent text-blue-600 font-medium text-sm leading-snug uppercase rounded hover:text-blue-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none focus:ring-0 active:bg-gray-200 transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light" role="button" rel="nofollow">
                        {{ __('Telegram') }}
                    </a>
                    <a href="https://vk.com/zdrava.online" target="_blank" class="inline-block px-7 py-3 bg-transparent text-blue-600 font-medium text-sm leading-snug uppercase rounded hover:text-blue-700 hover:bg-gray-100 focus:bg-gray-100 focus:outline-none focus:ring-0 active:bg-gray-200 transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light" role="button" rel="nofollow">
                        {{ __('VK') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-2 text-center justify-items-center">
            <div class="w-1/2">
                <div class="mt-6">
                    <a href="https://pay.cloudtips.ru/p/b9682fbc" target="_blank" class="inline-block px-7 py-3 mr-2 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light" role="button" rel="nofollow">
                        {{ __('Support the developer') }}
                    </a>
                    <p class="mt-3 text-gray-500 dark:text-gray-400">
                        донатом через CloudTips или через
                    </p>
                    <p class="mt-3">
                        <a href="https://vk.com/donut/zdrava.online" target="_blank" class="inline-block px-7 py-3 mr-2 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out" data-mdb-ripple="true" data-mdb-ripple-color="light" role="button" rel="nofollow">
                            {{ __('VK Donut') }}
                        </a>
                    </p>
                </div>
            </div>
            <div class="w-1/2">
                <figure class="max-w-lg">
                    <img class="h-auto max-w-1/2 rounded-lg" src="/qr-tinkoff.png" alt="{{ __('Support the developer') }}">
                    <figcaption class="mt-2 text-sm text-center text-gray-500 dark:text-gray-400">{{ __('Donate with QR-code') }}</figcaption>
                </figure>
            </div>
        </div>
    </main>
@endsection
