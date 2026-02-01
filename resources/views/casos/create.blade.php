@extends('layouts.app')

@section('title', 'Registar Novo Caso - MEDEREDE')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
<div class="page-header compact">
    <h1>📝 Registar Novo Caso</h1>
    <div class="breadcrumb">Dashboard > Casos > Novo Caso</div>
</div>

<div class="card">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->has('bilhete'))
        <div class="alert alert-error">
            {{ $errors->first('bilhete') }}
        </div>
    @endif
    <div class="card-header">
        <h2>Preencha os Dados do Caso</h2>
    </div>

    <form method="POST" action="/casos" class="compact-form">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="paciente_nome">👤 Nome do Paciente</label>
                <input type="text" id="paciente_nome" name="paciente_nome" class="form-control" placeholder="Ex: João Silva" required>
            </div>

            <div class="form-group">
                <label for="bilhete">📋 Bilhete</label>
                <input type="text" id="bilhete" name="bilhete" class="form-control" placeholder="Ex: CC12345678" value="{{ old('bilhete') }}">
            </div>

            <div class="form-group">
                <label for="data_nascimento">📅 Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" value="{{ old('data_nascimento') }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="doenca_id">🦠 Doença</label>
                <select id="doenca_id" name="doenca_id" class="form-control" required>
                    <option value="">-- Selecionar Doença --</option>
                    @foreach($doencas as $doenca)
                        <option value="{{ $doenca->id }}">
                            {{ $doenca->nome }} ({{ $doenca->codigo }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status">📊 Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="suspeito">🟡 Suspeito</option>
                    <option value="confirmado">🔴 Confirmado</option>
                    <option value="descartado">🟢 Descartado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="data_inicio">📅 Data de Início dos Sintomas</label>
                <input type="date" id="data_inicio" name="data_inicio" class="form-control" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="provincia">📍 Província</label>
                <input type="text" id="provincia" name="provincia" class="form-control" placeholder="Ex: Huíla" value="{{ old('provincia') }}" required>
            </div>

            <div class="form-group">
                <label for="municipio">🏙️ Município</label>
                <input type="text" id="municipio" name="municipio" class="form-control" placeholder="Ex: Matala" value="{{ old('municipio') }}" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="latitude">🧭 Latitude</label>
                <input type="text" id="latitude" name="latitude" class="form-control" placeholder="Ex: -11.7835" value="{{ old('latitude') }}" inputmode="decimal">
            </div>

            <div class="form-group">
                <label for="longitude">🧭 Longitude</label>
                <input type="text" id="longitude" name="longitude" class="form-control" placeholder="Ex: 16.3390" value="{{ old('longitude') }}" inputmode="decimal">
            </div>
        </div>

        <div class="form-group">
            <label for="sintomas">🤒 Sintomas Apresentados</label>
            <textarea id="sintomas" name="sintomas" class="form-control" placeholder="Descreva os sintomas observados..." required></textarea>
        </div>

        <div class="card map-card">
            <h3>🗺️ Mapa do Caso</h3>
            <div class="map-actions">
                <div class="map-search">
                    <input type="text" id="map-search" class="form-control" placeholder="Pesquisar local...">
                    <button type="button" id="map-search-btn" class="btn btn-secondary">🔎 Encontrar</button>
                </div>
                <button type="button" id="map-locate" class="btn btn-secondary">📍 Minha localização</button>
            </div>
            <div id="map" class="map-embed"></div>
            <p class="map-help">Clique no mapa para preencher as coordenadas e sugerir a província e o município.</p>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-success">✅ Registar Caso</button>
            <a href="/dashboard" class="btn btn-secondary">❌ Cancelar</a>
        </div>
    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // Auto-popular data com data de hoje se não preenchida
    document.getElementById('data_inicio').valueAsDate = new Date();

    const provinciaInput = document.getElementById('provincia');
    const municipioInput = document.getElementById('municipio');
    const latInput = document.getElementById('latitude');
    const lonInput = document.getElementById('longitude');
    const mapSearchInput = document.getElementById('map-search');
    const mapSearchBtn = document.getElementById('map-search-btn');
    const mapLocateBtn = document.getElementById('map-locate');

    const defaultCenter = [-11.2027, 17.8739];
    const map = L.map('map').setView(defaultCenter, 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    let marker = null;
    let reverseTimer = null;

    function setMarker(lat, lon, zoom = true) {
        if (!marker) {
            marker = L.marker([lat, lon], { draggable: true }).addTo(map);
            marker.on('drag', (event) => {
                const pos = event.target.getLatLng();
                setInputs(pos.lat, pos.lng);
            });
            marker.on('dragend', (event) => {
                const pos = event.target.getLatLng();
                updateFromLatLng(pos.lat, pos.lng, true, true, false);
            });
        } else {
            marker.setLatLng([lat, lon]);
        }
        if (zoom) {
            map.setView([lat, lon], 13);
        }
    }

    function setInputs(lat, lon) {
        latInput.value = lat.toFixed(6);
        lonInput.value = lon.toFixed(6);
    }

    function applyAddress(address) {
        if (!address) return;
        const provincia = address.state || address.region || address.county || '';
        const municipio = address.city || address.town || address.village || address.municipality || address.county || '';
        if (provincia) provinciaInput.value = provincia;
        if (municipio) municipioInput.value = municipio;
    }

    function updateFromLatLng(lat, lon, updateInputs = true, doReverse = true, zoom = true) {
        if (updateInputs) {
            setInputs(lat, lon);
        }
        setMarker(lat, lon, zoom);
        if (doReverse) {
            reverseGeocode(lat, lon);
        }
    }

    function reverseGeocode(lat, lon) {
        if (reverseTimer) {
            clearTimeout(reverseTimer);
        }
        reverseTimer = setTimeout(() => {
            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`)
                .then((response) => response.ok ? response.json() : null)
                .then((data) => {
                    if (!data || !data.address) return;
                    applyAddress(data.address);
                })
                .catch(() => {});
        }, 300);
    }

    map.on('click', (event) => {
        updateFromLatLng(event.latlng.lat, event.latlng.lng);
    });

    function forwardGeocode(query) {
        mapSearchBtn.disabled = true;
        fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(query)}&limit=1&addressdetails=1`)
            .then((response) => response.ok ? response.json() : null)
            .then((data) => {
                if (!data || data.length === 0) {
                    alert('Localização não encontrada.');
                    return;
                }
                const result = data[0];
                const lat = parseFloat(result.lat);
                const lon = parseFloat(result.lon);
                updateFromLatLng(lat, lon, true, false);
                if (result.address) {
                    applyAddress(result.address);
                } else {
                    reverseGeocode(lat, lon);
                }
            })
            .catch(() => {})
            .finally(() => {
                mapSearchBtn.disabled = false;
            });
    }

    function locateUser() {
        if (!navigator.geolocation) {
            alert('Geolocalização não suportada neste navegador.');
            return;
        }
        mapLocateBtn.disabled = true;
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                updateFromLatLng(pos.coords.latitude, pos.coords.longitude);
                mapLocateBtn.disabled = false;
            },
            () => {
                mapLocateBtn.disabled = false;
                alert('Não foi possível obter a localização.');
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }

    mapSearchBtn.addEventListener('click', () => {
        const query = mapSearchInput.value.trim();
        if (query) forwardGeocode(query);
    });

    mapSearchInput.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            const query = mapSearchInput.value.trim();
            if (query) forwardGeocode(query);
        }
    });

    mapLocateBtn.addEventListener('click', locateUser);

    function syncFromInputs() {
        const latRaw = (latInput.value || '').replace(/,/g, '.');
        const lonRaw = (lonInput.value || '').replace(/,/g, '.');
        if (latRaw !== latInput.value) latInput.value = latRaw;
        if (lonRaw !== lonInput.value) lonInput.value = lonRaw;
        const lat = parseFloat(latRaw);
        const lon = parseFloat(lonRaw);
        if (Number.isFinite(lat) && Number.isFinite(lon)) {
            updateFromLatLng(lat, lon, false);
        }
    }

    latInput.addEventListener('input', syncFromInputs);
    lonInput.addEventListener('input', syncFromInputs);

    syncFromInputs();
</script>
<style>
    .page-header.compact {
        margin-top: -10px;
        margin-bottom: 20px;
    }
    .compact-form .form-row {
        gap: 12px;
    }
    .compact-form .form-group {
        margin-bottom: 12px;
    }
    .compact-form textarea.form-control {
        min-height: 80px;
    }
    .map-card {
        background: #f8f9fa;
        border-left: 4px solid #667eea;
        margin: 12px 0;
        padding: 16px;
    }
    .map-card h3 {
        color: #667eea;
        margin-bottom: 10px;
    }
    .map-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        margin-bottom: 10px;
    }
    .map-search {
        display: flex;
        gap: 8px;
        flex: 1 1 280px;
    }
    .map-search input {
        flex: 1;
        min-width: 160px;
    }
    .map-embed {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        overflow: hidden;
        height: 280px;
        background: #fff;
    }
    .map-help {
        margin-top: 8px;
        color: #666;
        font-size: 0.9em;
    }
</style>
@endsection

