@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 py-12">
        <div class="flex">
            <div class="w-full">
                <!-- Контент -->
                <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black">
                    {{ __(':user - subscriptions', ['user' => $user->getFullName()]) }}
                </h2>
                {{-- TODO: Поиск--}}
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{ __('User') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @if (!count($user->subscriptions))
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" colspan="9" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {!! __('Subscriptions not found. :friend.find', [
                                            'friend.find' => '<a href="' . route('friends.find') . '" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">' . __('Find some using search') . '</a>',
                                    ]) !!}
                                </th>
                            </tr>
                        @else
                            @foreach($user->subscriptions as $suser)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <a href="{{ route('athlete.profile', $suser->id) }}">
                                            {{ $suser->getFullName() }}
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection
