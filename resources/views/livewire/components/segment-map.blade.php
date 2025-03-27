<div>
    <div wire:model="position" id="map" class="center-block z-0 mt-2 w-full h-[650px]"></div>
    <script nonce="{{ csp_nonce() }}">
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

        document.addEventListener("DOMContentLoaded", function (event) {
            const getPreferredScheme = () => window?.matchMedia?.('(prefers-color-scheme:dark)')?.matches ? 'dark' : 'light';

            map = L.map('map', {
                center: {
                    lat: userLat,
                    lng: userLon,
                },
                zoom: 14
            });

            map.on('moveend', function () {
                if (map.getZoom() >= 11) {
                    let bounds = map.getBounds();

                    Livewire.emit('mapBoundsChanged', {
                        north: bounds.getNorth(),
                        south: bounds.getSouth(),
                        east: bounds.getEast(),
                        west: bounds.getWest()
                    });
                } else {
                    let msg = '{{ trans('Your selected area is too large!') }}';
                    Toaster.warning(msg);
                }
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                className: getPreferredScheme() === 'dark' ? 'map-tiles-dark' : '',
            }).addTo(map);

            let myFGMarker = new L.FeatureGroup({chunkedLoading: true});
            let dataSet = JSON.parse({!!$segmentsJson!!});

            const markerOptions = {
                radius: 6,
                fillColor: "#ff5d34",
                color: "#000",
                weight: 1,
                opacity: 0.4,
                fillOpacity: 0.9,
            };

            function addSegmentToMap(item) {
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

                let marker = L.circleMarker([item.start_latlng.coordinates[1], item.start_latlng.coordinates[0]], markerOptions)
                    .addTo(myFGMarker);

                marker.bindPopup(popupText, {closeButton: false});
            }

            // Добавляем сегменты на карту
            dataSet.forEach((item) => {
                addSegmentToMap(item);
            });

            myFGMarker.addTo(map);
            map.fitBounds(myFGMarker.getBounds());

            Livewire.on('updateMap', (segments) => {
                segments = JSON.parse(segments);

                myFGMarker.clearLayers();

                segments.forEach((item) => {
                    addSegmentToMap(item);
                });

                myFGMarker.addTo(map);
            });
        });

        function openPopup(id) {
            map.eachLayer(layer => {
                if (layer instanceof L.Polyline) {
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
