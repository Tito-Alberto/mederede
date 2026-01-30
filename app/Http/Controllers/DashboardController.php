<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use App\Models\Caso;
use App\Models\Doenca;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas Gerais
        $casos = Caso::count();
        $doencas = Doenca::count();
        $alertasPendentes = Alerta::where('status', 'pendente')->count();
        $usuarios = User::count();

        // Evolução Temporal (Últimos 12 meses)
        $casosPorMes = Caso::selectRaw('DATE_FORMAT(data_inicio, "%Y-%m") as mes, count(*) as total')
            ->where('data_inicio', '>=', now()->startOfMonth()->subMonths(11))
            ->groupBy('mes')
            ->orderBy('mes')
            ->pluck('total', 'mes');

        // Distribuição por Doença
        $casosPorDoenca = Caso::selectRaw('doencas.nome, count(casos.id) as total')
            ->join('doencas', 'casos.doenca_id', '=', 'doencas.id')
            ->groupBy('doencas.nome')
            ->orderBy('total', 'DESC')
            ->get();

        // Distribuição por Status
        $casosPorStatus = Caso::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Mapa de Calor (Coordenadas)
        $casosCoordenadas = Caso::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->select('paciente_nome', 'latitude', 'longitude', 'status', 'localizacao')
            ->get();

        // Preparar dados para Chart.js
        $mesLabels = [];
        $mesData = [];
        $mesCursor = now()->startOfMonth()->subMonths(11);
        for ($i = 0; $i < 12; $i++) {
            $label = $mesCursor->format('Y-m');
            $mesLabels[] = $label;
            $mesData[] = (int) ($casosPorMes[$label] ?? 0);
            $mesCursor->addMonth();
        }

        $doencaLabels = $casosPorDoenca->pluck('nome')->toArray();
        $doencaData = $casosPorDoenca->pluck('total')->toArray();

        $statusOrder = ['confirmado', 'suspeito', 'descartado'];
        $statusLabels = array_map(function ($status) {
            return ucfirst($status);
        }, $statusOrder);
        $statusData = array_map(function ($status) use ($casosPorStatus) {
            return (int) ($casosPorStatus[$status] ?? 0);
        }, $statusOrder);

        // Alertas Recentes
        $alertasRecentes = Alerta::with('caso', 'user')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        // Casos Recentes
        $casosRecentes = Caso::with('doenca', 'user')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();

        // Taxa de Incidência (casos por 1000 habitantes - aproximado)
        $taxaIncidencia = $usuarios > 0 ? ($casos / $usuarios * 1000) : 0;

        $casosPorDoencaList = Caso::selectRaw('doencas.nome, count(casos.id) as total')
            ->join('doencas', 'casos.doenca_id', '=', 'doencas.id')
            ->groupBy('doencas.nome')
            ->orderBy('total', 'DESC')
            ->get();

        $casosCoordenadasCount = $casosCoordenadas->count();
        $mapMarkers = $casosCoordenadas->map(function ($caso) {
            return [
                'lat' => (float) $caso->latitude,
                'lng' => (float) $caso->longitude,
                'nome' => $caso->paciente_nome,
                'status' => $caso->status,
                'provincia' => $caso->provincia,
                'municipio' => $caso->municipio,
            ];
        })->values();
        $mapCenter = $mapMarkers->first() ?? ['lat' => -8.839987, 'lng' => 13.289437];
        return view('dashboard', compact(
            'casos', 'doencas', 'alertasPendentes', 'usuarios',
            'mesLabels', 'mesData',
            'doencaLabels', 'doencaData',
            'statusLabels', 'statusData',
            'alertasRecentes', 'casosRecentes',
            'casosCoordenadas',
            'taxaIncidencia',
            'casosPorDoencaList',
            'casosCoordenadasCount',
            'mapMarkers',
            'mapCenter'
        ));
    }
}
