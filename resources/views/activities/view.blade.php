@extends('layouts.site')
@section('title', $activity->name . ' | Zdrava')
@section('js')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet-src.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-gpx/1.7.0/gpx.min.js"></script>
@endsection

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
                <div class="flex pt-4">
                    <button id="dropdownButton" data-dropdown-toggle="dropdown" class="inline-block text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:ring-4 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-1.5" type="button">
                        <span class="sr-only">Open dropdown</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path></svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdown" class="z-10 hidden text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                        <ul class="py-2" aria-labelledby="dropdownButton">
                            @if ($activity->getUser()->id == auth()->user()->id || auth()->user()->hasRole('admin'))
                            <li>
                                <a href="{{ route('activities.edit', $activity->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    {{ __('Edit activity') }}
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ $activity->getGPXFile() }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    {{ __('Download :file', ['file' => 'GPX']) }}
                                </a>
                            </li>
                        </ul>
                        @if ($activity->getUser()->id == auth()->user()->id || auth()->user()->hasRole('admin'))
                        <div class="py-2">
                            <a href="#" onclick="event.preventDefault(); document.getElementById('activity-delete-form').submit();" class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                {{ __('Delete') }}
                            </a>
                            <form id="activity-delete-form" action="{{ route('activities.delete', $activity->id) }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="w-3/4">
                @error('search') @livewire('toast.errors') @enderror
                @if (Session::get('success'))
                    @livewire('toast.success')
                @endif
                <div id="accordion-flush" data-accordion="collapse" data-active-classes="bg-white dark:bg-gray-900 text-gray-900 dark:text-white" data-inactive-classes="text-gray-500 dark:text-gray-400">
                    <h2 id="accordion-flush-heading-overview">
                        <button type="button" class="flex items-center justify-between w-full px-4 py-5 font-medium text-left text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-overview" aria-expanded="true" aria-controls="accordion-flush-body-overview">
                            <span>
                                <a href="{{ route('athlete.profile', $activity->getUser()->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ $activity->getUser()->getFullName() }}</a> - Заезд
                            </span>
                            <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                    </h2>
                    <div id="accordion-flush-body-overview" class="hidden" aria-labelledby="accordion-flush-heading-overview">
                        <div class="flex flex-col items-start bg-white border border-gray-200 shadow md:flex-row max-w-full dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex flex-col justify-between p-4 leading-normal w-1/2">
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $activity->getLongStartDate() }}
                                    @if($activity->locality) - {{ $activity->locality }}@endif, {{ $activity->getCountry() }}
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
                                            {{ __(':distance km', ['distance' => $activity->getDistance()]) }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Distance') }}
                                        </span>
                                    </div>
                                    <div class="flex-1 mx-2 h-10">
                                        <h5 class="mb-0 text-2xl font-normal tracking-tight text-gray-900 dark:text-white">
                                            {{ $activity->getDuration() }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Duration') }}
                                        </span>
                                    </div>
                                    <div class="flex-1 mx-2 h-10">
                                        <h5 class="mb-0 text-2xl font-normal tracking-tight text-gray-900 dark:text-white">
                                            {{ $activity->getDurationTotal() }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Duration total') }}
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
                                </div>
                                <div class="flex my-4">
                                    <div class="flex-1 mx-2 h-10">
                                        <h5 class="mb-0 text-2xl font-normal tracking-tight text-gray-900 dark:text-white">
                                            {{ __(':speed km/h', ['speed' => $activity->getAverageSpeed()]) }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Avg. speed') }}
                                        </span>
                                    </div>
                                    <div class="flex-1 mx-2 h-10">
                                        <h5 class="mb-0 text-2xl font-normal tracking-tight text-gray-900 dark:text-white">
                                            {{ __(':speed km/h', ['speed' => $activity->getMaxSpeed()]) }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Max. speed') }}
                                        </span>
                                    </div>
                                    <div class="flex-1 mx-2 h-10">
                                        <h5 class="mb-0 text-2xl font-normal tracking-tight text-gray-900 dark:text-white">
                                            {{ $activity->avg_heart_rate }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Avg. HR') }}
                                        </span>
                                    </div>
                                    <div class="flex-1 mx-2 h-10">
                                        <h5 class="mb-0 text-2xl font-normal tracking-tight text-gray-900 dark:text-white">
                                            {{ $activity->avg_cadence }}
                                        </h5>
                                        <span class="text-gray-500 dark:text-gray-400">
                                            {{ __('Avg. cadence') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="w-full mt-3">
                                    <hr />
                                    <p class="mb-3 mt-3 font-normal text-gray-700 dark:text-gray-400">
                                        {{ $activity->getDeviceManufacturer() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full pt-4">
                    <div id="map" class="center-block" style="width: 100%; height: 400px;"></div>
                    <script>
                        let map, markers = [];
                        /* ----------------------------- Initialize Map ----------------------------- */
                        function initMap() {

                            map = L.map('map', {
                                center: {
                                    lat: {{ $activity->getTrackCenter()['lat'] }},
                                    lng: {{ $activity->getTrackCenter()['long'] }},
                                },
                                zoom: 12
                            });

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '© OpenStreetMap'
                            }).addTo(map);

                            L.Control.Button = L.Control.extend({
                                options: {
                                    position: 'topright'
                                },
                                onAdd: function (map) {
                                    var container = L.DomUtil.create('div', 'leaflet-control text-center content-center flex justify-center items-center');
                                    var button = L.DomUtil.create('div', 'leaflet-control-button border-2 border-black text-black bg-white', container);
                                    button.innerHTML = '<button class="mt-1"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg></button>';
                                    container.title = "{{ __('Download :file', ['file' => 'GPX']) }}";
                                    L.DomEvent.disableClickPropagation(button);
                                    L.DomEvent.on(button, 'click', function(){
                                        var fileUrl = '{{ $activity->getGPXFile() }}';
                                        var fileName = '{{ $activity->getGPXFile(true) }}';
                                        var link = document.createElement('a');
                                        link.href = fileUrl;
                                        link.download = fileName;
                                        link.click();
                                    });

                                    return container;
                                },
                                onRemove: function(map) {},
                            });
                            var control = new L.Control.Button()
                            control.addTo(map);

                            let gpx = '{{ $activity->getGPXFile() }}';
                            new L.GPX(gpx, {
                                async: true,
                                marker_options: {
                                    iconSize: [24, 24],
                                    iconAnchor: [12, 12],
                                    startIconUrl: '/storage/images/pin-start.png',
                                    endIconUrl: '/storage/images/pin-finish.png',
                                    shadowUrl: null
                                },
                                polyline_options: {
                                    color: 'red',
                                    opacity: 0.75,
                                    weight: 5,
                                    lineCap: 'round',
                                }
                            }).on('loaded', function(e) {
                                map.fitBounds(e.target.getBounds());
                            }).addTo(map);
                        }
                        initMap();
                    </script>
                </div>
                @livewire('comments.comments', ['activityId' => $activity->id])
            </div>
        </div>
    </main>
@endsection
