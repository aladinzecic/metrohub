import './bootstrap';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import './datetime';

document.addEventListener('DOMContentLoaded', function () {

    const mapEl = document.getElementById('map');
    if (!mapEl) return;

    const isDispatcherMap = window.LINES !== undefined && window.LOCATIONS_URL !== undefined;

    const map = L.map('map', {
        zoomControl: false,
        scrollWheelZoom: true,
    }).setView([44.8176, 20.4569], 13);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        attribution: '© OpenStreetMap © CARTO',
        maxZoom: 19
    }).addTo(map);

    L.control.zoom({ position: 'bottomright' }).addTo(map);

    function initMap(stations, lines) {
        const stationMarkers = {};
        let activePolyline   = null;

        stations.forEach((station) => {
            const lat = station.lat || station.latitude;
            const lng = station.lng || station.longitude;
            const key = `${lat}_${lng}`;

            if (!stationMarkers[key]) {
                const marker = L.circleMarker([lat, lng], {
                    radius: 6,
                    fillColor: '#0a0a0a',
                    color: '#0a0a0a',
                    weight: 2,
                    fillOpacity: 1
                })
                .addTo(map)
                .bindTooltip(station.name, {
                    permanent: false,
                    direction: 'top',
                    offset: [0, -10],
                    className: 'station-label'
                });

                stationMarkers[key] = {
                    marker,
                    name: station.name,
                    lat,
                    lng,
                };
            }
        });

        function drawLine(line) {
            if (activePolyline) {
                map.removeLayer(activePolyline);
                activePolyline = null;
            }

            if (!line.stations || line.stations.length < 2) return;

            const coords = line.stations
                .map(s => (s.lng || s.longitude) + ',' + (s.lat || s.latitude))
                .join(';');

            fetch(`https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`)
                .then(r => r.json())
                .then(data => {
                    if (!data.routes || !data.routes.length) return;

                    const leafletCoords = data.routes[0].geometry.coordinates
                        .map(c => [c[1], c[0]]);

                    activePolyline = L.polyline(leafletCoords, {
                        color: '#0a0a0a',
                        weight: 4,
                        opacity: 0.9,
                    }).addTo(map);

                    map.fitBounds(activePolyline.getBounds(), { padding: [60, 60] });
                })
                .catch(err => console.error('OSRM greška:', err));
        }

        function clearLine() {
            if (activePolyline) {
                map.removeLayer(activePolyline);
                activePolyline = null;
            }
        }

        const searchInput = document.querySelector('.map-search');
        if (searchInput) {
            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    clearLine();
                    return;
                }

                if (e.key !== 'Enter') return;

                const query = this.value.trim().toLowerCase();

                if (!query) {
                    clearLine();
                    return;
                }

                if (lines && lines.length > 0) {
                    const foundLine = lines.find(l =>
                        l.code.toLowerCase().includes(query) ||
                        l.name.toLowerCase().includes(query)
                    );

                    if (foundLine) {
                        drawLine(foundLine);
                        return;
                    }
                }

                const foundStation = Object.values(stationMarkers).find(s =>
                    s.name.toLowerCase().includes(query)
                );

                if (foundStation) {
                    clearLine();
                    map.setView([foundStation.lat, foundStation.lng], 18);
                    foundStation.marker.openTooltip();
                }
            });

            searchInput.addEventListener('input', function () {
                if (!this.value.trim()) {
                    clearLine();
                }
            });
        }

        return stationMarkers;
    }

    if (isDispatcherMap) {

        const allStations = [];
        if (window.LINES && window.LINES.length > 0) {
            window.LINES.forEach(line => {
                if (!line.stations) return;
                line.stations.forEach(s => allStations.push(s));
            });
        }

        initMap(allStations, window.LINES);

        const busMarkers = {};

        function loadVehicles() {
            if (!window.LOCATIONS_URL) return;

            fetch(window.LOCATIONS_URL)
                .then(r => {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(vehicles => {
                    vehicles.forEach(vehicle => {
                        if (!vehicle.lat || !vehicle.lng) return;

                        const busIcon = L.divIcon({
                            className: '',
                            html: `
                                <div style="display:flex;flex-direction:column;align-items:center;gap:3px;">
                                    <div style="background:#fff;border:1px solid #e2ddd8;border-radius:6px;padding:2px 6px;font-size:10px;font-family:'DM Sans',sans-serif;font-weight:600;color:#0a0a0a;white-space:nowrap;box-shadow:0 2px 6px rgba(0,0,0,0.1);">
                                        ${vehicle.name}
                                    </div>
                                    <div style="width:14px;height:14px;border-radius:50%;background:#0a0a0a;border:3px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.3);cursor:pointer;"></div>
                                </div>
                            `,
                            iconSize: [60, 36],
                            iconAnchor: [30, 36],
                        });

                        if (busMarkers[vehicle.id]) {
                            busMarkers[vehicle.id].marker.setLatLng([vehicle.lat, vehicle.lng]);
                            busMarkers[vehicle.id].marker.setIcon(busIcon);
                            busMarkers[vehicle.id].data = vehicle;
                        } else {
                            const marker = L.marker([vehicle.lat, vehicle.lng], { icon: busIcon }).addTo(map);

                            marker.on('click', function () {
                                showVehiclePanel(busMarkers[vehicle.id].data);
                            });

                            busMarkers[vehicle.id] = { marker, data: vehicle };
                        }
                    });
                })
                .catch(err => console.error('Vehicles fetch error:', err));
        }

        loadVehicles();
        setInterval(loadVehicles, 5000);

        function showVehiclePanel(vehicle) {
            const panel = document.getElementById('vehicle-panel');
            const title = document.getElementById('vehicle-panel-title');
            const sub   = document.getElementById('vehicle-panel-sub');
            const list  = document.getElementById('vehicle-panel-stations');

            if (!panel) return;

            title.textContent = `${vehicle.name}${vehicle.line_code ? ' · ' + vehicle.line_code : ''}`;
            sub.textContent   = `${vehicle.plate} · ${vehicle.speed} km/h${vehicle.line_name ? ' · ' + vehicle.line_name : ''}`;

            list.innerHTML = '';

            if (!vehicle.stations || vehicle.stations.length === 0) {
                list.innerHTML = '<p style="font-size:12px;color:#999993;padding:8px 0;">Nema podataka o stanicama.</p>';
                panel.style.display = 'block';
                return;
            }

            vehicle.stations.forEach((station, index) => {
                const isCurrent = index === vehicle.current_station_index;
                const isPassed  = index < vehicle.current_station_index;

                const item = document.createElement('div');
                item.className = 'map-stop';
                item.innerHTML = `
                    <div class="map-stop-left">
                        <div class="circle" style="background:${isCurrent ? '#0a0a0a' : '#bdbdaf'};"></div>
                        <h2 style="color:${isCurrent ? '#0a0a0a' : '#919182'};">
                            ${station.name}
                            ${isCurrent ? '<span style="font-size:10px;background:#0a0a0a;color:#fff;padding:2px 7px;border-radius:10px;margin-left:6px;">Trenutna</span>' : ''}
                        </h2>
                    </div>
                    ${isCurrent ? '<p style="font-size:11px;color:#0a0a0a;font-weight:500;">Sada</p>' : isPassed ? '<p style="font-size:11px;color:#bdbdaf;">Prošla</p>' : ''}
                `;
                list.appendChild(item);

                if (index < vehicle.stations.length - 1) {
                    const vert = document.createElement('div');
                    vert.className = 'vertical';
                    list.appendChild(vert);
                }
            });

            panel.style.display = 'block';
        }

    } else {

        if (window.LINES && window.LINES.length > 0) {
            const allStations = [];
            window.LINES.forEach(line => {
                if (!line.stations) return;
                line.stations.forEach(s => allStations.push(s));
            });
            initMap(allStations, window.LINES);
        }
    }

});