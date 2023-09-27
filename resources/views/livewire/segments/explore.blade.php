@section('js')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet-src.js"></script>
    <script src="https://unpkg.com/polyline-encoded@0.0.9/Polyline.encoded.js"></script>
@endsection
<main class="container mx-auto px-0 py-12 max-w-screen-lg">
    <div class="grid grid-cols-1 gap-4">
        <div class="w-full">
            <h3 class="text-3xl font-bold dark:text-white">{{ __('Explore segments') }}</h3>
            @error('admin') @livewire('toast.errors') @enderror
            @if (Session::get('success'))
                @livewire('toast.success')
            @endif
        </div>
    </div>
    <div class="grid grid-cols-3 gap-4 mt-6">
        <div class="w-full">
            <h4 class="text-2xl font-bold dark:text-white">{{ __('Segments') }}</h4>
            <div class="mt-6">
                <ul class="max-w-md">
                    @foreach($segments as $segment)
                        <li class="pb-3 sm:pb-4">
                            <a href="#">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        {{ Str::limit($segment->name, 30, ' ...') }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                        &Delta; {{ __('Elevation') }}: {{ __(':elevation m', ['elevation' => $segment->total_elevation_gain]) }}
                                    </p>
                                </div>
                                <div class="inline-flex items-center text-base font-semibold text-gray-600 dark:text-white">
                                    {{ __(':distance m', ['distance' => $segment->distance]) }}
                                </div>
                            </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="w-full col-span-2">
            <div id="map" class="center-block z-0" style="width: 100%; height: 650px;"></div>
            <script>
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            initMap(position.coords.latitude, position.coords.longitude);
                        },
                        (error) => {
                            console.error("Error getting user location:", error);
                        }
                    );
                } else {
                    console.error("Geolocation is not supported by this browser.");
                }

                let map, markers = [];
                function initMap(uLat, uLng) {
                    map = L.map('map', {
                        center: {
                            lat: uLat,
                            lng: uLng,
                        },
                        zoom: 12
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    let segmentsLayer = L.layerGroup();
                    let myFGMarker = new L.FeatureGroup();
                    let dataSet = JSON.parse({!!json_encode($segments->getCollection()->toJson())!!});

                    const markerOptions = {
                        radius: 6,
                        fillColor: "#ff653e",
                        color: "#000",
                        weight: 1,
                        opacity: 0.6,
                        fillOpacity: 0.9,
                    };

                    dataSet.forEach((item) => {
                        let polyline = L.Polyline.fromEncoded(item.polyline, {
                            weight: 4,
                            color: '#ff5d34',
                            opacity: 0.7
                        }).addTo(segmentsLayer);

                        let latLng = item.start_latlng.split(',');
                        L.circleMarker([latLng[0], latLng[1]], markerOptions).addTo(myFGMarker);
                    });

                    map.addLayer(segmentsLayer);
                    myFGMarker.addTo(map);
                    map.fitBounds(myFGMarker.getBounds());
                }
            </script>
        </div>
    </div>
    <div class="grid grid-cols-1 gap-4">
        <div class="w-full">
            <div class="mt-6">
                {{ $segments->onEachSide(0)->links() }}
            </div>
        </div>
    </div>
</main>
