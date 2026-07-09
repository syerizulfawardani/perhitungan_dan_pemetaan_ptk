<x-layouts.app>

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Peta Sebaran PTK per Kecamatan</h4>
            <p class="text-muted mb-0 small">Kabupaten Ketapang &mdash; Visualisasi persebaran PTK berdasarkan wilayah kecamatan</p>
        </div>
    </div>

    {{-- Peta --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h6 class="mb-0 fw-semibold">
                        <i class="ti ti-map-2 me-2 text-primary"></i>Persebaran PTK per Kecamatan &mdash; Kabupaten Ketapang
                    </h6>
                    <div class="d-flex align-items-center gap-2 small text-muted">
                        <span class="badge" style="background:#e8f4f8;color:#333">0</span>
                        <span class="badge" style="background:#b3d9ff;color:#333">Rendah</span>
                        <span class="badge" style="background:#4da6ff;color:#fff">Sedang</span>
                        <span class="badge" style="background:#0066cc;color:#fff">Tinggi</span>
                        <span class="badge" style="background:#003d7a;color:#fff">&gt; 30</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="mapPTK" style="height:580px;border-radius:0 0 8px 8px;"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        .leaflet-layer-control { background:#fff; padding:10px 12px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,.15); font-family:inherit; min-width:150px; }
        .leaflet-layer-control .llc-title { font-weight:600; font-size:12px; color:#333; margin-bottom:4px; }
        .leaflet-layer-control .llc-section + .llc-section { margin-top:10px; padding-top:8px; border-top:1px solid #eee; }
        .leaflet-layer-control label { display:flex; align-items:center; gap:6px; font-size:12.5px; color:#444; padding:2px 0; cursor:pointer; }
        .leaflet-layer-control input[type="radio"] { margin:0; cursor:pointer; }
    </style>

    <script>
    const datasets = {
        semua: @json($ptkPerKecamatan),
        paud:  @json($ptkPaud),
        sd:    @json($ptkSd),
        smp:   @json($ptkSmp),
    };
    const datasetLabels = { semua: 'Semua Jenjang', paud: 'PAUD', sd: 'SD', smp: 'SMP' };
    const sekolahData = @json($sekolahPerKecamatan);
    let currentDatasetKey = 'semua';
    let currentData = datasets[currentDatasetKey];

    const map = L.map('mapPTK', { zoomControl: true }).setView([-1.85, 110.15], 8);

    const normalLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 16,
    }).addTo(map);

    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics, and the GIS User Community',
        maxZoom: 18,
    });

    function getColor(n) {
        if (n === 0)   return '#e8f4f8';
        if (n <= 5)    return '#b3d9ff';
        if (n <= 15)   return '#4da6ff';
        if (n <= 30)   return '#0066cc';
        return '#003d7a';
    }

    function style(feature) {
        const nama  = feature.properties.nama_kecamatan;
        const total = currentData[nama] ?? 0;
        return {
            fillColor:   getColor(total),
            weight:      1.5,
            opacity:     1,
            color:       '#ffffff',
            dashArray:   '',
            fillOpacity: 0.75,
        };
    }

    function popupContent(nama) {
        const total   = currentData[nama] ?? 0;
        const sekolah = sekolahData[nama] ?? { paud: 0, sd: 0, smp: 0 };
        return `
            <div style="min-width:190px">
                <strong style="font-size:14px">${nama}</strong><br>
                <span style="color:#666;font-size:12px">Kabupaten Ketapang</span>
                <hr style="margin:6px 0">
                <div style="font-size:13px">Jumlah PTK (${datasetLabels[currentDatasetKey]}): <b style="color:#0066cc">${total}</b></div>
                <hr style="margin:6px 0">
                <div style="font-size:12px;font-weight:600;margin-bottom:3px">Jumlah Sekolah</div>
                <table style="font-size:12px;width:100%;border-collapse:collapse">
                    <tr><td>PAUD</td><td style="text-align:right;font-weight:600">${sekolah.paud}</td></tr>
                    <tr><td>SD</td><td style="text-align:right;font-weight:600">${sekolah.sd}</td></tr>
                    <tr><td>SMP</td><td style="text-align:right;font-weight:600">${sekolah.smp}</td></tr>
                </table>
            </div>
        `;
    }

    let geojsonLayer;

    // Info panel
    const infoControl = L.control({ position: 'topright' });
    infoControl.onAdd = function () {
        this._div = L.DomUtil.create('div', 'leaflet-info-panel');
        this._div.style.cssText = 'background:white;padding:10px 14px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.15);min-width:160px;font-family:inherit;';
        this.update();
        return this._div;
    };
    infoControl.update = function (props) {
        const nama  = props ? props.nama_kecamatan : null;
        const total = props ? (currentData[nama] ?? 0) : null;
        this._div.innerHTML = nama
            ? `<strong style="font-size:13px">${nama}</strong><br><span style="font-size:12px;color:#666">Jumlah PTK: <b style="color:#0066cc">${total}</b></span>`
            : '<span style="font-size:12px;color:#888">Arahkan ke kecamatan</span>';
    };
    infoControl.addTo(map);

    // Legend
    const legend = L.control({ position: 'bottomright' });
    legend.onAdd = function () {
        const div = L.DomUtil.create('div');
        div.style.cssText = 'background:white;padding:10px 14px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.15);font-size:12px;';
        div.innerHTML = `
            <div class="legend-title" style="font-weight:600;margin-bottom:6px">Jumlah PTK &mdash; ${datasetLabels[currentDatasetKey]}</div>
            ${[['#e8f4f8','0'],['#b3d9ff','1 – 5'],['#4da6ff','6 – 15'],['#0066cc','16 – 30'],['#003d7a','> 30']]
                .map(([c, l]) => `<div style="display:flex;align-items:center;gap:6px;margin-bottom:3px">
                    <span style="width:16px;height:16px;border-radius:3px;background:${c};display:inline-block;border:1px solid #ccc"></span>
                    <span>${l}</span></div>`).join('')}
        `;
        return div;
    };
    legend.addTo(map);
    legend.update = function () {
        this._container.querySelector('.legend-title').innerHTML = `Jumlah PTK &mdash; ${datasetLabels[currentDatasetKey]}`;
    };

    // Layer control: peta dasar (normal/satelit) + data PTK (semua/paud/sd/smp)
    const layerControl = L.control({ position: 'topleft' });
    layerControl.onAdd = function () {
        const div = L.DomUtil.create('div', 'leaflet-layer-control');
        div.innerHTML = `
            <div class="llc-section">
                <div class="llc-title">Peta Dasar</div>
                <label><input type="radio" name="baseLayer" value="normal" checked> Normal</label>
                <label><input type="radio" name="baseLayer" value="satelit"> Satelit</label>
            </div>
        `;
        L.DomEvent.disableClickPropagation(div);

        div.querySelectorAll('input[name="baseLayer"]').forEach(input => {
            input.addEventListener('change', function () {
                if (this.value === 'satelit') {
                    map.removeLayer(normalLayer);
                    map.addLayer(satelliteLayer);
                } else {
                    map.removeLayer(satelliteLayer);
                    map.addLayer(normalLayer);
                }
            });
        });

        div.querySelectorAll('input[name="dataLayer"]').forEach(input => {
            input.addEventListener('change', function () {
                currentDatasetKey = this.value;
                currentData = datasets[currentDatasetKey];
                legend.update();
                infoControl.update();
                if (geojsonLayer) {
                    geojsonLayer.eachLayer(layer => {
                        layer.setStyle(style(layer.feature));
                        layer.setPopupContent(popupContent(layer.feature.properties.nama_kecamatan));
                    });
                }
            });
        });

        return div;
    };
    layerControl.addTo(map);

    // Merge semua polygon per kecamatan menjadi 1 MultiPolygon
    function mergeByKecamatan(data) {
        const grouped = {};
        data.features.forEach(f => {
            const nama = f.properties.nama_kecamatan;
            if (!grouped[nama]) {
                grouped[nama] = {
                    type: 'Feature',
                    properties: f.properties,
                    geometry: { type: 'MultiPolygon', coordinates: [] }
                };
            }
            const g = f.geometry;
            if (g.type === 'Polygon') {
                grouped[nama].geometry.coordinates.push(g.coordinates);
            } else if (g.type === 'MultiPolygon') {
                g.coordinates.forEach(c => grouped[nama].geometry.coordinates.push(c));
            }
        });
        return { type: 'FeatureCollection', features: Object.values(grouped) };
    }

    // Load GeoJSON
    fetch('{{ asset("geojson/ketapang_kecamatan.json") }}')
        .then(r => r.json())
        .then(raw => {
            const data = mergeByKecamatan(raw);
            geojsonLayer = L.geoJSON(data, {
                style: style,
                onEachFeature: function (feature, layer) {
                    const nama = feature.properties.nama_kecamatan;

                    layer.on({
                        mouseover: function (e) {
                            const l = e.target;
                            l.setStyle({ weight: 3, color: '#333', fillOpacity: 0.9 });
                            l.bringToFront();
                            infoControl.update(feature.properties);
                        },
                        mouseout: function (e) {
                            geojsonLayer.resetStyle(e.target);
                            infoControl.update();
                        },
                        click: function (e) {
                            map.fitBounds(e.target.getBounds(), { padding: [30, 30] });
                        },
                    });

                    layer.bindPopup(popupContent(nama));
                }
            }).addTo(map);

            map.fitBounds(geojsonLayer.getBounds(), { padding: [20, 20] });
        })
        .catch(err => console.error('GeoJSON error:', err));
    </script>
    @endpush

</x-layouts.app>
