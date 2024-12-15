import polyUtil from "polyline-encoded";

document.addEventListener('alpine:init', () => {
    Alpine.data('mapComponent', ({ lat, lng, zoom = 12, polyline, gpxStartIcon, gpxEndIcon }) => ({
        map: null,
        init() {
            const getPreferredScheme = () => window?.matchMedia?.('(prefers-color-scheme:dark)')?.matches ? 'dark' : 'light';

            if (!lat || !lng || !polyline) {
                console.error('Не хватает обязательных параметров для инициализации карты');
                return;
            }

            if (this.map) {
                this.map.remove();
                this.map = null;
            }

            const mapElement = this.$el;
            this.map = L.map(mapElement, {
                center: { lat, lng },
                zoom,
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap',
                className: getPreferredScheme() === 'dark' ? 'map-tiles-dark' : '',
            }).addTo(this.map);

            let coords = polyUtil.decode(polyline);
            const polylineLayer = L.polyline(coords, {
                color: 'red',
                weight: 5,
                opacity: 0.75,
                lineCap: 'round',
            }).addTo(this.map);

            L.marker(coords[0], {
                icon: L.icon({
                    iconUrl: gpxStartIcon,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                }),
            }).addTo(this.map);

            L.marker(coords[coords.length - 1], {
                icon: L.icon({
                    iconUrl: gpxEndIcon,
                    iconSize: [24, 24],
                    iconAnchor: [12, 12],
                }),
            }).addTo(this.map);

            this.map.invalidateSize();
            this.map.fitBounds(polylineLayer.getBounds());
        },
    }));
});
