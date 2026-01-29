@extends('layouts.app')

@section('title', 'Alertas - MEDEREDE')

@section('content')
<div class="page-header">
    <h1>Alertas</h1>
    <div class="breadcrumb">Dashboard > Alertas</div>
</div>

<div class="card">
    @php
        $isPublic = Auth::user()->role === 'publico';
        $canManageAlertas = !$isPublic;
        $isAdmin = Auth::user()->role === 'admin';
    @endphp
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Lista de Alertas</h2>
        @if ($canManageAlertas)
            <a href="{{ route('alertas.create') }}" class="btn btn-primary" style="margin: 0;">Novo Alerta</a>
        @endif
    </div>

    <div style="margin-bottom: 20px; display: flex; gap: 10px;">
        <select class="form-control" style="max-width: 150px;">
            <option value="">Todos</option>
            <option value="pendente">Pendente</option>
            <option value="enviado">Enviado</option>
            <option value="falhou">Falhou</option>
        </select>
        <select class="form-control" style="max-width: 150px;">
            <option value="">Todos os Tipos</option>
            <option value="email">Email</option>
            <option value="sms">SMS</option>
            <option value="notificacao">Notificacao</option>
        </select>
    </div>

    @if ($alertas && $alertas->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Caso</th>
                    <th>Titulo</th>
                    <th>Tipo</th>
                    <th>Status</th>
                    <th>Data do Alerta</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alertas as $alerta)
                    @php
                        $tipoLabel = $alerta->tipo === 'sms' ? 'SMS' : ($alerta->tipo === 'email' ? 'Email' : 'Notificacao');
                        $statusClass = $alerta->status === 'pendente' ? 'badge-warning' : ($alerta->status === 'enviado' ? 'badge-success' : 'badge-danger');
                    @endphp
                    @php
                        $pacienteNome = $alerta->caso?->paciente_nome ?? '';
                        $pacienteMascarado = $pacienteNome !== '' ? (substr($pacienteNome, 0, 1) . str_repeat('*', max(0, strlen($pacienteNome) - 2)) . substr($pacienteNome, -1)) : 'N/A';
                        $pacienteExibido = $isPublic ? $pacienteMascarado : ($pacienteNome !== '' ? $pacienteNome : 'N/A');
                    @endphp
                    <tr>
                        <td>#{{ str_pad($alerta->caso_id, 3, '0', STR_PAD_LEFT) }} - {{ $pacienteExibido }}</td>
                        <td>{{ $alerta->titulo }}</td>
                        <td>{{ $tipoLabel }}</td>
                        <td><span class="badge {{ $statusClass }}">{{ ucfirst($alerta->status) }}</span></td>
                        <td>{{ optional($alerta->data_alerta)->format('d M Y - H:i') ?? 'N/A' }}</td>
                        <td>
                            @if ($canManageAlertas)
                                <a href="{{ route('alertas.edit', $alerta) }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.85em;">Editar</a>
                            @endif
                            @if ($isAdmin)
                                <form action="{{ route('alertas.destroy', $alerta) }}" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja eliminar este alerta?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.85em;">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $alertas->links() }}
        </div>
    @else
        <p style="color: #999; padding: 1rem;">Nenhum alerta registado</p>
    @endif
</div>

<div class="card">
    <div class="card-header">
        <h2>Resumo de Alertas</h2>
    </div>

    <div class="stats-grid" style="margin-bottom: 0;">
        <div class="stat-card">
            <div class="label">Alertas Totais</div>
            <div class="number">{{ $totalAlertas ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Pendentes</div>
            <div class="number">{{ $pendentes ?? 0 }}</div>
            <small style="color: #f59e0b;">Requerem atencao</small>
        </div>
        <div class="stat-card">
            <div class="label">Enviados</div>
            <div class="number">{{ $enviados ?? 0 }}</div>
        </div>
        <div class="stat-card">
            <div class="label">Falhados</div>
            <div class="number">{{ $falhou ?? 0 }}</div>
            <small style="color: #dc2626;">Retentar</small>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Distribuicao por Tipo</h2>
    </div>

    @php
        $total = ($totalAlertas ?? 0) > 0 ? $totalAlertas : 1;
        $emailCount = $tipoCounts['email'] ?? 0;
        $notificacaoCount = $tipoCounts['notificacao'] ?? 0;
        $smsCount = $tipoCounts['sms'] ?? 0;
    @endphp

    <table class="table" style="font-size: 0.95em;">
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Quantidade</th>
                <th>Percentagem</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Email</td>
                <td>{{ $emailCount }}</td>
                <td>{{ number_format(($emailCount / $total) * 100, 1) }}%</td>
            </tr>
            <tr>
                <td>Notificacao</td>
                <td>{{ $notificacaoCount }}</td>
                <td>{{ number_format(($notificacaoCount / $total) * 100, 1) }}%</td>
            </tr>
            <tr>
                <td>SMS</td>
                <td>{{ $smsCount }}</td>
                <td>{{ number_format(($smsCount / $total) * 100, 1) }}%</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
