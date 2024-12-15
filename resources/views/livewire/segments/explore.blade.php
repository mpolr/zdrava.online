@section('title', __('Explore segments') . ' | Zdrava')
@section('js')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet-src.js" nonce="{{ csp_nonce() }}"></script>
@endsection
<div class="container mx-auto px-0 py-12 max-w-screen-lg">
    <div class="grid grid-cols-1 gap-4">
        <div class="w-full">
            <h3 class="text-3xl font-bold dark:text-white">{{ __('Explore segments') }}</h3>
            @error('segments') @livewire('toast.errors') @enderror
            @if (Session::get('success'))
                @livewire('toast.success')
            @endif
        </div>
        <div class="w-full">
            <form>
                <div class="flex mt-6">
                    <label for="segment-search"
                           class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">{{ __('Search') }}</label>
                    <button wire:model="activityType" id="dropdown-button-segments"
                            data-dropdown-toggle="dropdown-search-segment"
                            class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-500 bg-gray-100 border border-gray-300 rounded-l-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600"
                            type="button">
                        @if ($activityType === 'Ride')
                            <svg aria-hidden="true" class="h-3 mr-2" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5.5 19.675a5.166 5.166 0 005.105-4.485h1.105l3.28-6.52.76 1.46a5.044 5.044 0 101.22-.57l-2.03-3.89H17a.333.333 0 01.33.33v.57h1.34V6A1.674 1.674 0 0017 4.32h-4.29l1.57 3.01H8.542L7.66 5.67h1.45l-.72-1.35H4.17l.72 1.35h1.241l1.26 2.37v.01l-.76 1.41a5.2 5.2 0 00-1.13-.135 5.175 5.175 0 100 10.35zm12.79-4.695h1.52l-2.2-4.2c.291-.073.59-.11.89-.11a3.83 3.83 0 11-3.83 3.83 3.877 3.877 0 011.7-3.19l1.92 3.67zm-4.82-6.31l-2.046 4.082-2.17-4.082h4.216zm-5.32.8l2.323 4.371H5.8l2.35-4.37zM5.5 10.675c.151.005.302.019.451.041l-1.58 2.944.79 1.53h4.1a3.822 3.822 0 11-3.76-4.515z"
                                      fill=""></path>
                            </svg>
                        @else
                            <svg aria-hidden="true" class="h-3.5 w-3.5 rounded-full mr-2"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M21.3 18.12L14.98 6.28a2.6 2.6 0 00-4.63.07l-.46.93a.585.585 0 01-.21-.45V3.17A2.452 2.452 0 007.24.72a2.172 2.172 0 00-2.01 1.4L2.91 6.84 1.39 7.96a2.768 2.768 0 00-1.06 2.06 2.96 2.96 0 00.9 2.32l7.76 7.9a11.62 11.62 0 008.22 3.43h3.65a2.757 2.757 0 002.41-1.4l.05-.09a2.7 2.7 0 00-.01-2.73 2.665 2.665 0 00-2.01-1.33zm.85 3.39l-.05.09a1.425 1.425 0 01-1.24.73h-3.65a10.257 10.257 0 01-7.26-3.04l-7.78-7.92a1.566 1.566 0 01-.49-1.27 1.426 1.426 0 01.5-1.05l.71-.53 8.6 8.48h1.64v-.28L3.98 7.7l2.48-5.02a.848.848 0 01.78-.61 1.1 1.1 0 011.09 1.1v3.66a1.92 1.92 0 001.92 1.92h.43l.88-1.8a1.24 1.24 0 011.12-.7 1.257 1.257 0 011.11.67l1.04 1.94L12.69 10v1.52l2.77-1.47.77 1.42v.01l-2.63 1.39v1.53l3.26-1.73.74 1.37-3.02 1.6v1.53l3.65-1.94 2.06 3.85.25.36h.4a1.376 1.376 0 011.2.69 1.34 1.34 0 01.01 1.38z"
                                      fill=""></path>
                            </svg>
                        @endif
                        {{ __($activityType) }}
                        <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 4 4 4-4"/>
                        </svg>
                    </button>
                    <div id="dropdown-search-segment"
                         class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                            aria-labelledby="dropdown-button-segments">
                            <li>
                                <button type="button" wire:click="setActivityType('Ride')"
                                        class="inline-flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">
                                    <div class="inline-flex items-center">
                                        <svg aria-hidden="true" class="h-3.5 w-3.5 rounded-full mr-2"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M5.5 19.675a5.166 5.166 0 005.105-4.485h1.105l3.28-6.52.76 1.46a5.044 5.044 0 101.22-.57l-2.03-3.89H17a.333.333 0 01.33.33v.57h1.34V6A1.674 1.674 0 0017 4.32h-4.29l1.57 3.01H8.542L7.66 5.67h1.45l-.72-1.35H4.17l.72 1.35h1.241l1.26 2.37v.01l-.76 1.41a5.2 5.2 0 00-1.13-.135 5.175 5.175 0 100 10.35zm12.79-4.695h1.52l-2.2-4.2c.291-.073.59-.11.89-.11a3.83 3.83 0 11-3.83 3.83 3.877 3.877 0 011.7-3.19l1.92 3.67zm-4.82-6.31l-2.046 4.082-2.17-4.082h4.216zm-5.32.8l2.323 4.371H5.8l2.35-4.37zM5.5 10.675c.151.005.302.019.451.041l-1.58 2.944.79 1.53h4.1a3.822 3.822 0 11-3.76-4.515z"
                                                  fill=""></path>
                                        </svg>
                                        {{ __('Ride') }}
                                    </div>
                                </button>
                            </li>
                            <li>
                                <button type="button" wire:click="setActivityType('Run')"
                                        class="inline-flex w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                        role="menuitem">
                                    <div class="inline-flex items-center">
                                        <svg aria-hidden="true" class="h-3.5 w-3.5 rounded-full mr-2"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M21.3 18.12L14.98 6.28a2.6 2.6 0 00-4.63.07l-.46.93a.585.585 0 01-.21-.45V3.17A2.452 2.452 0 007.24.72a2.172 2.172 0 00-2.01 1.4L2.91 6.84 1.39 7.96a2.768 2.768 0 00-1.06 2.06 2.96 2.96 0 00.9 2.32l7.76 7.9a11.62 11.62 0 008.22 3.43h3.65a2.757 2.757 0 002.41-1.4l.05-.09a2.7 2.7 0 00-.01-2.73 2.665 2.665 0 00-2.01-1.33zm.85 3.39l-.05.09a1.425 1.425 0 01-1.24.73h-3.65a10.257 10.257 0 01-7.26-3.04l-7.78-7.92a1.566 1.566 0 01-.49-1.27 1.426 1.426 0 01.5-1.05l.71-.53 8.6 8.48h1.64v-.28L3.98 7.7l2.48-5.02a.848.848 0 01.78-.61 1.1 1.1 0 011.09 1.1v3.66a1.92 1.92 0 001.92 1.92h.43l.88-1.8a1.24 1.24 0 011.12-.7 1.257 1.257 0 011.11.67l1.04 1.94L12.69 10v1.52l2.77-1.47.77 1.42v.01l-2.63 1.39v1.53l3.26-1.73.74 1.37-3.02 1.6v1.53l3.65-1.94 2.06 3.85.25.36h.4a1.376 1.376 0 011.2.69 1.34 1.34 0 01.01 1.38z"
                                                  fill=""></path>
                                        </svg>
                                        {{ __('Run') }}
                                    </div>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="relative w-full">
                        <input wire:model="search" type="search" id="segment-search"
                               class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-r-lg border-l-gray-50 border-l-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-l-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                               placeholder="{{ __('Search for segment by name') }}" required>
                        <button type="button" wire:click="search"
                                class="absolute top-0 right-0 h-full p-2.5 text-sm font-medium text-white bg-blue-700 rounded-r-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                            <span class="sr-only">{{ __('Search') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4 mt-3">
        <div class="w-full">
            <h4 class="text-2xl font-bold dark:text-white">{{ __('Segments') }}</h4>
            <div class="mt-3">
                <ul class="max-w-md">
                    @foreach($segments as $segment)
                        <li id="segment-{{ $segment->id }}" class="pb-4 sm:pb-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                    <a href="#" onclick="openPopup({{ $segment->id }});">
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                            {{ Str::limit($segment->name, 30, ' ...') }}
                                        </p>
                                    </a>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                        &Delta; {{ __('Elevation gain') }}
                                        : {{ __(':elevation m', ['elevation' => $segment->total_elevation_gain]) }}
                                        <a href="#" wire:click="segmentDownloadFIT({{ $segment->id }})"
                                           class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ __('FIT') }}</a>
                                    </p>
                                </div>
                                <div
                                    class="inline-flex items-center text-base font-semibold text-gray-600 dark:text-white">
                                    {{ __(':distance m', ['distance' => $segment->distance]) }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="w-full col-span-2">
            <livewire:components.segment-map :segments="$segments"></livewire:components.segment-map>
        </div>
    </div>
</div>
