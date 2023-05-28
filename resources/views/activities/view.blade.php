@extends('layouts.site')
@section('content')
    <main class="container mx-auto px-0 py-12">
        <div class="flex">
            <div class="w-1/4 px-4">
                <!-- Боковое меню -->
                <div class="max-w-xs flex flex-col rounded-md shadow-sm">
                    <a href="#" type="button" class="py-3 px-4 inline-flex justify-left items-center gap-2 rounded-t-md border font-medium bg-white text-gray-700 align-middle hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all text-sm dark:bg-gray-800 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400">
                        {{ __('Overview') }}
                    </a>
                    <a href="#" type="button" class="-mt-px py-3 px-4 inline-flex justify-left items-center gap-2 border font-medium bg-white text-gray-700 align-middle hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-blue-600 transition-all text-sm dark:bg-gray-800 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400">
                        {{ __('Analysis') }}
                    </a>
                </div>
            </div>
            <div class="w-3/4">
                <div id="accordion-flush" data-accordion="collapse" data-active-classes="bg-white dark:bg-gray-900 text-gray-900 dark:text-white" data-inactive-classes="text-gray-500 dark:text-gray-400">
                    <h2 id="accordion-flush-heading-overview">
                        <button type="button" class="flex items-center justify-between w-full px-4 py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-overview" aria-expanded="true" aria-controls="accordion-flush-body-overview">
                            <span>
                                <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </a> - {{ $activity->name }}
                            </span>
                            <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </h2>
                    <div id="accordion-flush-body-overview" class="hidden" aria-labelledby="accordion-flush-heading-overview">
                        <div class="flex flex-col items-center bg-white border border-gray-200 shadow md:flex-row max-w-full dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex flex-col justify-between p-4 leading-normal w-1/2">
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($activity->started_at)->translatedFormat('d F Y г., l, H:i') }} - Россия
                                </p>
                                <h4 class="mb-2 mt-0 text-3xl font-medium leading-tight text-black">
                                    {{ $activity->name }}
                                </h4>
                                {{ $activity->description }}
                            </div>
                            <div class="flex flex-col justify-between p-4 leading-normal w-1/2">
                                <div class="flex">
                                    <div class="flex-1 mx-2 h-12">
                                        <h5 class="mb-0 text-2xl font-normal tracking-tight text-gray-900 dark:text-white">
                                            {{ __(':distance km', ['distance' => number_format($activity->distance, 2, ',')]) }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Distance') }}
                                        </span>
                                    </div>
                                    <div class="flex-1 mx-2 h-10">
                                        <h5 class="mb-0 text-2xl font-normal tracking-tight text-gray-900 dark:text-white">
                                            {{ number_format($activity->duration / 60, 2, ':') }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Duration') }}
                                        </span>
                                    </div>
                                    <div class="flex-1 mx-2 h-10">
                                        <h5 class="mb-0 text-2xl font-normal tracking-tight text-gray-900 dark:text-white">
                                            {{ __(':elevation m', ['elevation' => $activity->elevation_gain]) }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Elevation') }}
                                        </span>
                                    </div>
                                    <div class="flex-1 mx-2 h-10">
                                        <h5 class="mb-0 text-2xl font-normal tracking-tight text-gray-900 dark:text-white">
                                            {{ __(':speed km/h', ['speed' => number_format($activity->avg_speed, 1, ',')]) }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Avg. speed') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full mt-3">
                                    <hr />
                                    <p class="mb-3 mt-3 font-normal text-gray-700 dark:text-gray-400">
                                        {{ $manufacturer }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 id="accordion-flush-heading-analysis">
                        <button type="button" class="flex items-center justify-between w-full px-4 py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-analysis" aria-expanded="false" aria-controls="accordion-flush-body-analysis">
                            <span>{{ __('Analysis') }}</span>
                            <svg data-accordion-icon class="w-6 h-6 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </h2>
                    <div id="accordion-flush-body-analysis" class="hidden" aria-labelledby="accordion-flush-heading-analysis">
                        <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                            <p class="mb-2 text-gray-500 dark:text-gray-400">Тут будет карта и анализ тренировки</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
