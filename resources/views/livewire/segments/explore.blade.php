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
    <div class="grid grid-cols-1 gap-4">
        <div class="w-full">
            <div id="map" class="center-block" style="width: 100%; height: 650px;"></div>
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

                    let dataSet = JSON.parse({!!json_encode($segments->toJson())!!});
                    dataSet.forEach((item) => {
                        let polyline = L.Polyline.fromEncoded(item.polyline, {
                            weight: 4,
                            color: '#f30'
                        }).addTo(map);
                    })

                    let encoded = "ouruIkvk~Gg@iFk@{HWkB[_EU}AEq@KaA@MKkBC{A?gADWCY@o@Ce@FoAHeEXwEl@qFb@kGNoAPs@d@eEJkB^cDRgEf@gDRmBHgALw@Bw@ReBd@{DP{@Dq@PkAd@oE`@sCd@cBHQx@aDd@wAVm@Ro@P_@v@_CVo@X_Ab@mBt@mBr@iCd@wADYTs@Pc@x@kCd@kAb@{@`@eA`@yAr@sBTaAb@wAXkAPqADqA@sAFwCCmC?cF@i@AeBDwBDcAQuEOmAe@gCWiA_A_G]wAWwAo@aEaAcF[wBw@{DMe@[oBcAaFOgBUwAe@iEi@gDc@sDOyAe@kHQ}AEu@?uCB}ALmCPsBFgARoBHcCXmC@m@NgCTeCHyB@_At@}JPqFT_C@]NiBXqEPyAJwBLmDJmAb@gHBqBFsAAqASgBg@aFGgAIi@";


                    //map.fitBounds(polyline.getBounds());
                }
            </script>
        </div>
    </div>
</main>
