<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Relatorio - MEDEREDE</title>
    <style>
        body { font-family: Arial, sans-serif; color: #111; font-size: 12px; margin: 24px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #eef0f4; padding-bottom: 12px; margin-bottom: 10px; }
        .brand-title { font-size: 16px; font-weight: bold; letter-spacing: 0.5px; }
        .brand-sub { color: #555; font-size: 11px; margin-top: 2px; }
        .meta { text-align: right; font-size: 11px; color: #333; }
        .meta span { color: #666; }
        h1 { font-size: 18px; margin: 10px 0 6px; }
        .muted { color: #666; }
        .section { margin-top: 14px; }
        h2 { font-size: 13px; margin: 0 0 6px; border-left: 3px solid #667eea; padding-left: 8px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px; text-align: left; }
        th { background: #f5f6f8; font-weight: bold; }
        tr:nth-child(even) td { background: #fafafa; }
        .empty { color: #666; font-style: italic; padding: 6px 0; }
    </style>
</head>
<body>
    @php
        $isPublic = Auth::user()?->role === 'publico';
        $maskName = function ($name) {
            $name = $name ?? '';
            if ($name === '') {
                return 'N/A';
            }
            return substr($name, 0, 1) . str_repeat('*', max(0, strlen($name) - 2)) . substr($name, -1);
        };
    @endphp

    <div class="header">
        <div>
            <div class="brand-title">MEDEREDE</div>
            <div class="brand-sub">Relatorio epidemiologico</div>
        </div>
        <div class="meta">
            <div><span>Periodo:</span> {{ $periodoLabel }} ({{ $inicio->format('d/m/Y') }} - {{ $fim->format('d/m/Y') }})</div>
            <div><span>Gerado:</span> {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    @if(in_array('casos', $sections))
        <div class="section">
            <h2>Casos Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Doenca</th>
                        <th>Status</th>
                        <th>Localizacao</th>
                        <th>Data Inicio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($casos as $caso)
                        <tr>
                            <td>{{ $caso->id }}</td>
                            <td>{{ $isPublic ? $maskName($caso->paciente_nome) : $caso->paciente_nome }}</td>
                            <td>{{ $caso->doenca?->nome ?? 'N/A' }}</td>
                            <td>{{ $caso->status }}</td>
                            <td>{{ $caso->localizacao }}</td>
                            <td>{{ optional($caso->data_inicio)->format('d/m/Y') ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">Nenhum registro no periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if(in_array('alertas', $sections))
        <div class="section">
            <h2>Alertas</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Caso</th>
                        <th>Titulo</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alertas as $alerta)
                        <tr>
                            <td>{{ $alerta->id }}</td>
                            <td>#{{ $alerta->caso_id }}</td>
                            <td>{{ $alerta->titulo }}</td>
                            <td>{{ $alerta->tipo }}</td>
                            <td>{{ $alerta->status }}</td>
                            <td>{{ optional($alerta->data_alerta)->format('d/m/Y H:i') ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">Nenhum registro no periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if(in_array('evolucao', $sections))
        <div class="section">
            <h2>Evolucao Temporal</h2>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Total de Casos</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($evolucaoLabels as $idx => $label)
                        <tr>
                            <td>{{ $label }}</td>
                            <td>{{ $evolucaoData[$idx] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="empty">Nenhum registro no periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if(in_array('distribuicao_doencas', $sections))
        <div class="section">
            <h2>Distribuicao de Doencas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Doenca</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distribuicaoDoencas as $item)
                        <tr>
                            <td>{{ $item->nome }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="empty">Nenhum registro no periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if(in_array('casos_status', $sections))
        <div class="section">
            <h2>Casos por Status</h2>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($casosStatus as $status => $total)
                        <tr>
                            <td>{{ ucfirst($status) }}</td>
                            <td>{{ $total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="empty">Nenhum registro no periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if(in_array('alertas_recentes', $sections))
        <div class="section">
            <h2>Alertas Recentes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titulo</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alertasRecentes as $alerta)
                        <tr>
                            <td>{{ $alerta->id }}</td>
                            <td>{{ $alerta->titulo }}</td>
                            <td>{{ $alerta->tipo }}</td>
                            <td>{{ $alerta->status }}</td>
                            <td>{{ optional($alerta->data_alerta)->format('d/m/Y H:i') ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty">Nenhum registro no periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if(in_array('casos_recentes', $sections))
        <div class="section">
            <h2>Casos Recentes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>Doenca</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($casosRecentes as $caso)
                        <tr>
                            <td>{{ $caso->id }}</td>
                            <td>{{ $isPublic ? $maskName($caso->paciente_nome) : $caso->paciente_nome }}</td>
                            <td>{{ $caso->doenca?->nome ?? 'N/A' }}</td>
                            <td>{{ $caso->status }}</td>
                            <td>{{ optional($caso->data_inicio)->format('d/m/Y') ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty">Nenhum registro no periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if(in_array('dados_geograficos', $sections))
        <div class="section">
            <h2>Dados Geograficos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                        <th>Localizacao</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dadosGeograficos as $item)
                        <tr>
                            <td>{{ $isPublic ? $maskName($item->paciente_nome) : $item->paciente_nome }}</td>
                            <td>{{ $item->latitude }}</td>
                            <td>{{ $item->longitude }}</td>
                            <td>{{ $item->localizacao }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="empty">Nenhum registro no periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif

    @if(in_array('resumo_doencas', $sections))
        <div class="section">
            <h2>Resumo por Doencas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Doenca</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resumoDoencas as $item)
                        <tr>
                            <td>{{ $item->nome }}</td>
                            <td>{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="empty">Nenhum registro no periodo.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</body>
</html>
