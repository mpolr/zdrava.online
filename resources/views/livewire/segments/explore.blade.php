@section('title', __('Explore segments') . ' | Zdrava')
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
    <div class="grid grid-cols-3 gap-4 mt-3">
        <div class="w-full">
            <h4 class="text-2xl font-bold dark:text-white">{{ __('Segments') }}</h4>
            <div class="mt-3">
                <ul class="max-w-md">
                    @foreach($segments as $segment)
                        <li id="segment-{{ $segment->id }}" class="pb-4 sm:pb-4">
                            <a href="#" onclick="openPopup({{ $segment->id }});">
                            <div class="flex items-center space-x-4">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                        {{ Str::limit($segment->name, 30, ' ...') }}
                                    </p>
                                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                        &Delta; {{ __('Elevation gain') }}: {{ __(':elevation m', ['elevation' => $segment->total_elevation_gain]) }} <span wire:click="segmentDownloadFIT({{ $segment->id }})">{{ __('FIT') }}</span>
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
            <div id="map" class="center-block z-0 mt-2" style="width: 100%; height: 650px;"></div>
            <script>
                let map = [];
                let userLat = 0;
                let userLon = 0;

                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            userLat = position.coords.latitude;
                            userLon = position.coords.longitude
                        },
                        (error) => {
                            console.error("Error getting user location:", error);
                        }
                    );
                } else {
                    console.error("Geolocation is not supported by this browser.");
                }

                document.addEventListener("DOMContentLoaded", function(event) {
                    map = L.map('map', {
                        center: {
                            lat: userLat,
                            lng: userLon,
                        },
                        zoom: 12
                    });

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    let myFGMarker = new L.FeatureGroup();
                    let dataSet = JSON.parse({!!json_encode($segments->getCollection()->toJson())!!});

                    const markerOptions = {
                        radius: 6,
                        fillColor: "#ff5d34",
                        color: "#000",
                        weight: 1,
                        opacity: 0.4,
                        fillOpacity: 0.9,
                    };

                    dataSet.forEach((item) => {
                        let popupText = `<b>${item.name}</b><hr class="h-px my-2 bg-gray-200 border-0 dark:bg-gray-700">{{ __('Distance') }}: ${item.distance} m<br>
                            {{ __('Elevation gain') }}: ${item.total_elevation_gain}<br>`;

                        let polyline = L.Polyline.fromEncoded(item.polyline, {
                            weight: 4,
                            color: '#ff5d34',
                            opacity: 0.4
                        }).addTo(myFGMarker).on({
                            click: function (e) {
                                map.eachLayer(layer => {
                                    if (layer instanceof L.Polyline) {
                                        layer.setStyle({
                                            opacity: 0.4,
                                        });
                                    }
                                })
                                this.setStyle({
                                    opacity: 1.0,
                                });
                            }
                        });

                        polyline.bindPopup(popupText, {closeButton: false});

                        polyline.id = item.id;

                        let latLng = item.start_latlng.split(',');
                        let marker = L.circleMarker([latLng[0], latLng[1]], markerOptions)
                            .addTo(myFGMarker);

                        marker.bindPopup(popupText, {closeButton: false});
                    });

                    myFGMarker.addTo(map);
                    map.fitBounds(myFGMarker.getBounds());
                });

                function openPopup(id) {
                    map.eachLayer(layer => {
                        if (layer instanceof L.Polyline){
                            layer.setStyle({
                                opacity: 0.4,
                            });
                        }
                        if (layer.id === id && layer instanceof L.Polyline) {
                            layer.setStyle({
                                opacity: 1.0,
                            });
                            map.fitBounds(layer.getBounds());
                            layer.openPopup();
                        }
                    });
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
