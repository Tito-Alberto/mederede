@extends('layouts.app')

@section('title', 'Dashboard - MEDEREDE')

@section('content')
@php
    $isPublic = Auth::user()->role === 'publico';
@endphp
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 8px;
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border-left: 4px solid #667eea;
    }

    .stat-card .label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .stat-card .number {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
    }

    .card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        padding: 1.5rem;
    }

    .card-header {
        margin-bottom: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 1rem;
    }

    .card-header h2 {
        margin: 0;
        color: #333;
        font-size: 1.3rem;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
        gap: 20px;
    }

    .chart-container {
        position: relative;
        height: 400px;
        margin-bottom: 2rem;
    }
</style>

<div class="page-header">
    <h1>📊 Dashboard de Monitorização</h1>
    <div style="margin-top: 0.5rem; opacity: 0.9;">Visão geral do sistema MEDEREDE em tempo real</div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Doenças Monitoradas</div>
        <div class="number">{{ $doencas }}</div>
        <small style="color: #999;">Ativas no sistema</small>
    </div>

    <div class="stat-card">
        <div class="label">Casos Registados</div>
        <div class="number">{{ $casos }}</div>
        <small style="color: #999;">Total no sistema</small>
    </div>

    <div class="stat-card">
        <div class="label">Alertas Pendentes</div>
        <div class="number">{{ $alertasPendentes }}</div>
        <small style="color: #999;">Requerem atenção</small>
    </div>

    <div class="stat-card">
        <div class="label">Utilizadores Ativos</div>
        <div class="number">{{ $usuarios }}</div>
        <small style="color: #999;">No sistema</small>
    </div>

</div>
<!-- Gráficos com Chart.js -->
<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h2>📈 Evolução Temporal (12 Meses)</h2>
        </div>
        <div class="chart-container">
            <canvas id="casosMesChart"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>🦠 Distribuição por Doença</h2>
        </div>
        <div class="chart-container">
            <canvas id="doencasChart"></canvas>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>📊 Casos por Status</h2>
    </div>
    <div style="position: relative; height: 300px;">
        <canvas id="statusChart"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>🚨 Alertas Recentes</h2>
    </div>
    @if($alertasRecentes && $alertasRecentes->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Caso</th>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alertasRecentes as $alerta)
                    @php
                        $pacienteNome = $alerta->caso?->paciente_nome ?? '';
                        $pacienteMascarado = $pacienteNome !== '' ? (substr($pacienteNome, 0, 1) . str_repeat('*', max(0, strlen($pacienteNome) - 2)) . substr($pacienteNome, -1)) : 'N/A';
                        $pacienteExibido = $isPublic ? $pacienteMascarado : ($pacienteNome !== '' ? $pacienteNome : 'N/A');
                    @endphp
                    <tr>
                        <td>#{{ $alerta->caso_id }} - {{ $pacienteExibido }}</td>
                        <td>{{ $alerta->titulo }}</td>
                        <td><span class="badge badge-info">{{ ucfirst($alerta->tipo) }}</span></td>
                        <td><span class="badge badge-warning">{{ ucfirst($alerta->status) }}</span></td>
                        <td>{{ optional($alerta->data_alerta)->format('d M Y') ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #999; padding: 1rem;">ℹ️ Nenhum alerta registado</p>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <h2>📝 Casos Recentes</h2>
    </div>
    @if($casosRecentes && $casosRecentes->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Doença</th>
                    <th>Província</th>
                    <th>Município</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($casosRecentes as $caso)
                    @php
                        $nome = $caso->paciente_nome ?? '';
                        $nomeMascarado = $nome !== '' ? (substr($nome, 0, 1) . str_repeat('*', max(0, strlen($nome) - 2)) . substr($nome, -1)) : 'N/A';
                        $nomeExibido = $isPublic ? $nomeMascarado : $nome;
                    @endphp
                    <tr>
                        <td>{{ $nomeExibido }}</td>
                        <td><strong>{{ $caso->doenca?->nome ?? 'N/A' }}</strong></td>
                        <td>{{ $caso->provincia }}</td>
                        <td>{{ $caso->municipio }}</td>
                        <td>
                            @if($caso->status === 'confirmado')
                                <span class="badge" style="background: #f8d7da; color: #721c24;">Confirmado</span>
                            @elseif($caso->status === 'suspeito')
                                <span class="badge" style="background: #fff3cd; color: #856404;">Suspeito</span>
                            @else
                                <span class="badge" style="background: #d4edda; color: #155724;">Descartado</span>
                            @endif
                        </td>
                        <td>{{ optional($caso->data_inicio)->format('d M Y') ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #999; padding: 1rem;">Nenhum caso registado</p>
    @endif
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
    
    
    <div class="card">
        <div class="card-header">
            <h2>Dados Geograficos (OpenStreetMap)</h2>
        </div>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
        <div id="mederede-map" style="height: 200px; border-radius: 5px;"></div>
        <div style="margin-top: 0.5rem; color: #666;">
            {{ $casosCoordenadasCount ?? 0 }} casos com coordenadas
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>📊 Resumo por Doença</h2>
        </div>
        <div style="height: 200px; overflow-y: auto;">
            @if($casosPorDoencaList && $casosPorDoencaList->count() > 0)
                @foreach($casosPorDoencaList as $item)
                    <div style="padding: 0.75rem 0; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between;">
                        <span>{{ $item->nome }}</span>
                        <strong style="color: #667eea;">{{ $item->total }}</strong>
                    </div>
                @endforeach
            @else
                <p style="color: #999; padding: 1rem;">Nenhum dado disponÃ­vel</p>
            @endif
        </div>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    @php
        $canManageDashboard = Auth::user()->role !== 'publico';
    @endphp
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        @if ($canManageDashboard)
            <a href="/casos/create" class="btn btn-primary" style="background: #667eea; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">➕ Registar Novo Caso</a>
            <a href="/relatorios" class="btn btn-secondary" style="background: #6c757d; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">📄 Ver Relatórios</a>
        @endif
        @if ($canManageDashboard)
            <a href="/alertas" class="btn btn-secondary" style="background: #6c757d; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">🚨 Gerenciar Alertas</a>
        @endif
        @if ($canManageDashboard)
            <a href="{{ route('qrcode.list') }}" class="btn btn-info" style="background: #17a2b8; color: white; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">🔐 Validação QR Code</a>
        @endif
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
    function loadChartJs(callback) {
        if (window.Chart) {
            callback();
            return;
        }
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
        script.onload = callback;
        document.head.appendChild(script);
    }

    function initCharts() {
        // Grafico de Evolucao Temporal
        @if(count($mesLabels) > 0)
            const ctMesesEl = document.getElementById('casosMesChart');
            if (ctMesesEl) {
                const ctMeses = ctMesesEl.getContext('2d');
                new Chart(ctMeses, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($mesLabels) !!},
                        datasets: [{
                            label: 'Casos Registados',
                            data: {!! json_encode($mesData) !!},
                            borderColor: '#667eea',
                            backgroundColor: 'rgba(102, 126, 234, 0.1)',
                            tension: 0.3,
                            fill: true,
                            pointRadius: 6,
                            pointBackgroundColor: '#667eea',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: true, position: 'top' } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        @endif

        // Grafico de Doencas
        @if(count($doencaLabels) > 0)
            const ctDoencasEl = document.getElementById('doencasChart');
            if (ctDoencasEl) {
                const ctDoencas = ctDoencasEl.getContext('2d');
                new Chart(ctDoencas, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($doencaLabels) !!},
                        datasets: [{
                            data: {!! json_encode($doencaData) !!},
                            backgroundColor: ['#667eea', '#764ba2', '#f093fb', '#10b981', '#f59e0b'],
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom' } }
                    }
                });
            }
        @endif

        // Grafico de Status
        @if(count($statusLabels) > 0)
            const ctStatusEl = document.getElementById('statusChart');
            if (ctStatusEl) {
                const ctStatus = ctStatusEl.getContext('2d');
                new Chart(ctStatus, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($statusLabels) !!},
                        datasets: [{
                            label: 'Numero de Casos',
                            data: {!! json_encode($statusData) !!},
                            backgroundColor: ['rgba(239, 68, 68, 0.8)', 'rgba(245, 158, 11, 0.8)', 'rgba(16, 185, 129, 0.8)'],
                            borderColor: ['rgb(239, 68, 68)', 'rgb(245, 158, 11)', 'rgb(16, 185, 129)'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true } }
                    }
                });
            }
        @endif
    }

    window.addEventListener('load', () => {
        loadChartJs(initCharts);
    });
</script>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const mederedeMapMarkers = @json($mapMarkers);
    const mederedeMapCenter = @json($mapCenter);
    const mapEl = document.getElementById('mederede-map');

    if (mapEl && window.L) {
        const hasMarkers = Array.isArray(mederedeMapMarkers) && mederedeMapMarkers.length > 0;
        const centerLat = mederedeMapCenter.lat || -8.839987;
        const centerLng = mederedeMapCenter.lng || 13.289437;
        const map = L.map(mapEl).setView([centerLat, centerLng], hasMarkers ? 8 : 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const statusColors = {
            confirmado: '#ef4444',
            suspeito: '#f59e0b',
            descartado: '#10b981',
        };

        if (hasMarkers) {
            const bounds = L.latLngBounds();
            mederedeMapMarkers.forEach((marker) => {
                if (marker && marker.lat && marker.lng) {
                    const color = statusColors[marker.status] || '#667eea';
                    const circle = L.circleMarker([marker.lat, marker.lng], {
                        radius: 6,
                        color: '#ffffff',
                        weight: 2,
                        fillColor: color,
                        fillOpacity: 1,
                    }).addTo(map)
                      .bindPopup(`<strong>${marker.nome || 'Paciente'}</strong><br>Status: ${marker.status || 'N/A'}<br>${marker.provincia || ''}${marker.municipio ? ' - ' + marker.municipio : ''}`);
                    bounds.extend(circle.getLatLng());
                }
            });
            if (bounds.isValid()) {
                map.fitBounds(bounds, { padding: [20, 20] });
            }
        }
    }
</script>
@endsection
