@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 py-12">
        <div class="flex">
            <div class="w-full">
                <!-- Контент -->
                <h2 class="mb-2 mt-0 text-4xl font-medium leading-tight text-black">
                    {{ __('My workouts') }}
                </h2>
{{--                // TODO: Поиск--}}
                <h4 class="mb-2 mt-0 text-3xl font-medium leading-tight text-black">
                    {{ __('Workouts: :count', ['count' => count($activities)]) }}
                </h4>
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Type') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        {{ __('Date') }}
                                        <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        {{ __('Name') }}
                                        <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        {{ __('Time') }}
                                        <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        {{ __('Distance') }}
                                        <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    <div class="flex items-center">
                                        {{ __('Altitude') }}
                                        <a href="#"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 ml-1" aria-hidden="true" fill="currentColor" viewBox="0 0 320 512"><path d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z"/></svg></a>
                                    </div>
                                </th>
                                <th scope="col" class="px-0 py-3">
                                    <span class="sr-only">{{ __('Edit') }}</span>
                                </th>
                                <th scope="col" class="px-0 py-3">
                                    <span class="sr-only">{{ __('Delete') }}</span>
                                </th>
                                <th scope="col" class="px-0 py-3">
                                    <span class="sr-only">{{ __('Share') }}</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!count($activities))
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" colspan="9" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        {!! __('Workouts not found. Record one using :zdrava-app or :upload manually', [
                                                'zdrava-app' => '<a href="' . route('app') . '" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">' . __('Zdrava android app') . '</a>',
                                                'upload' => '<a href="' . route('upload.workout') . '" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">' . __('upload files') . ' GPX, FIT, TCX</a>'
                                        ])!!}
                                    </th>
                                </tr>
                            @else
                                @foreach($activities as $activity)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $activity->type }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $activity->getShortStartDate() }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('activities.get', $activity->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                {{ $activity->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $activity->getDuration() }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ __(':distance km', ['distance' => $activity->getDistance()]) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ __(':elevation m', ['elevation' => $activity->elevation_gain]) }}
                                        </td>
                                        <td class="px-0 py-4 text-right">
                                            <a href="{{ route('activities.edit', $activity->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Edit') }}</a>
                                        </td>
                                        <td class="px-0 py-4 text-right">
                                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Delete') }}</a>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ __('Share') }}</a>
                                        </td>
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
