@extends('layouts.app')

@section('title', 'Casos - MEDEREDE')

@section('content')
<div class="page-header">
    <h1>📝 Casos Registados</h1>
    <div class="breadcrumb">Dashboard > Casos</div>
</div>

<div class="card">
    @php
        $isPublic = Auth::user()->role === 'publico';
        $canManageCasos = !$isPublic;
        $isAdmin = Auth::user()->role === 'admin';
    @endphp
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Lista de Casos</h2>
        @if ($canManageCasos)
            <a href="/casos/create" class="btn btn-primary" style="margin: 0;">➕ Novo Caso</a>
        @endif
    </div>

    <!-- Formulário de Filtros -->
    <form method="GET" action="/casos" style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 10px; margin-bottom: 15px;">
            <!-- Filtro por Status -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">🔍 Status</label>
                <select name="status" class="form-control" onchange="this.form.submit();">
                    <option value="">-- Todos --</option>
                    <option value="suspeito" {{ request('status') === 'suspeito' ? 'selected' : '' }}>🟡 Suspeito</option>
                    <option value="confirmado" {{ request('status') === 'confirmado' ? 'selected' : '' }}>🔴 Confirmado</option>
                    <option value="descartado" {{ request('status') === 'descartado' ? 'selected' : '' }}>🟢 Descartado</option>
                </select>
            </div>

            <!-- Filtro por Doença -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">🦠 Doença</label>
                <select name="doenca_id" class="form-control" onchange="this.form.submit();">
                    <option value="">-- Todas --</option>
                    @foreach($doencas as $doenca)
                        <option value="{{ $doenca->id }}" {{ request('doenca_id') == $doenca->id ? 'selected' : '' }}>
                            {{ $doenca->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtro por Data -->
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">📅 Data (De)</label>
                <input type="date" name="data_de" class="form-control" value="{{ request('data_de') }}" onchange="this.form.submit();">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">📅 Data (Até)</label>
                <input type="date" name="data_ate" class="form-control" value="{{ request('data_ate') }}" onchange="this.form.submit();">
            </div>
        </div>

        <!-- Pesquisa por Província, Município e Nome -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">📍 Província</label>
                <input type="text" name="provincia" class="form-control" placeholder="Pesquisar província..." value="{{ request('provincia') }}">
            </div>

            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">🏙️ Município</label>
                <input type="text" name="municipio" class="form-control" placeholder="Pesquisar município..." value="{{ request('municipio') }}">
            </div>

            @if (!$isPublic)
                <div>
                <label style="display: block; margin-bottom: 5px; font-weight: bold; font-size: 0.9em;">👤 Nome do Paciente</label>
                <input type="text" name="paciente" class="form-control" placeholder="Pesquisar paciente..." value="{{ request('paciente') }}">
            </div>
            @endif

            <div style="display: flex; gap: 10px; align-items: flex-end;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">🔍 Pesquisar</button>
                <a href="/casos" class="btn btn-secondary" style="padding: 8px 15px;">✕ Limpar</a>
            </div>
        </div>
    </form>

    <!-- Resumo de Filtros Ativos -->
    @if(request()->anyFilled(['status', 'doenca_id', 'data_de', 'data_ate', 'provincia', 'municipio', 'paciente']))
        <div style="padding: 10px 15px; background: #e3f2fd; border-radius: 8px; border-left: 4px solid #2196f3; margin-bottom: 15px;">
            <strong>🔎 Filtros Ativos:</strong>
            @if(request('status'))
                <span style="display: inline-block; background: #2196f3; color: white; padding: 3px 8px; border-radius: 4px; margin: 0 5px;">Status: {{ ucfirst(request('status')) }}</span>
            @endif
            @if(request('doenca_id'))
                <span style="display: inline-block; background: #2196f3; color: white; padding: 3px 8px; border-radius: 4px; margin: 0 5px;">Doença: {{ $doencas->find(request('doenca_id'))->nome ?? '' }}</span>
            @endif
            @if(request('data_de'))
                <span style="display: inline-block; background: #2196f3; color: white; padding: 3px 8px; border-radius: 4px; margin: 0 5px;">De: {{ request('data_de') }}</span>
            @endif
            @if(request('data_ate'))
                <span style="display: inline-block; background: #2196f3; color: white; padding: 3px 8px; border-radius: 4px; margin: 0 5px;">Até: {{ request('data_ate') }}</span>
            @endif
            @if(request('provincia'))
                <span style="display: inline-block; background: #2196f3; color: white; padding: 3px 8px; border-radius: 4px; margin: 0 5px;">Província: {{ request('provincia') }}</span>
            @endif
            @if(request('municipio'))
                <span style="display: inline-block; background: #2196f3; color: white; padding: 3px 8px; border-radius: 4px; margin: 0 5px;">Município: {{ request('municipio') }}</span>
            @endif
            @if(!$isPublic && request('paciente'))
                <span style="display: inline-block; background: #2196f3; color: white; padding: 3px 8px; border-radius: 4px; margin: 0 5px;">Paciente: {{ request('paciente') }}</span>
            @endif
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Doença</th>
                <th>Província</th>
                <th>Município</th>
                <th>Status</th>
                <th>Data Início</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($casos as $caso)
            <tr>
                @php
                    $nome = $caso->paciente_nome ?? '';
                    $nomeMascarado = $nome !== '' ? (substr($nome, 0, 1) . str_repeat('*', max(0, strlen($nome) - 2)) . substr($nome, -1)) : 'N/A';
                    $nomeExibido = $isPublic ? $nomeMascarado : $nome;
                @endphp
                <td>{{ $nomeExibido }}</td>
                <td>{{ $caso->doenca->nome ?? 'N/A' }}</td>
                <td>{{ $caso->provincia }}</td>
                <td>{{ $caso->municipio }}</td>
                <td>
                    @if($caso->status === 'suspeito')
                        <span class="badge badge-warning">Suspeito</span>
                    @elseif($caso->status === 'confirmado')
                        <span class="badge badge-danger">Confirmado</span>
                    @else
                        <span class="badge badge-success">Descartado</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($caso->data_inicio)->format('d M Y') }}</td>
                <td>
                    <a href="/casos/{{ $caso->id }}" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.85em;">👁️ Ver</a>
                    @if ($canManageCasos)
                        <a href="/casos/{{ $caso->id }}/edit" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.85em;">✏️ Editar</a>
                    @endif
                    @if ($isAdmin)
                        <button onclick="if(confirm('Tem certeza que deseja eliminar este caso?')) { document.getElementById('delete-form-{{ $caso->id }}').submit(); }" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.85em;">🗑️ Eliminar</button>
                        <form id="delete-form-{{ $caso->id }}" action="/casos/{{ $caso->id }}" method="POST" style="display:none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">
                    <p style="color: #999;">
                        Nenhum caso registado.
                        @if ($canManageCasos)
                            <a href="/casos/create">Registar novo caso</a>
                        @endif
                    </p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
    <div class="card">
        <div class="card-header">
            <h2>📊 Resumo por Status</h2>
        </div>
        
        <table class="table" style="font-size: 0.95em;">
            <tbody>
                <tr>
                    <td>🟡 Suspeitos</td>
                    <td style="text-align: right; font-weight: bold;">8 (40%)</td>
                </tr>
                <tr>
                    <td>🔴 Confirmados</td>
                    <td style="text-align: right; font-weight: bold;">7 (35%)</td>
                </tr>
                <tr>
                    <td>🟢 Descartados</td>
                    <td style="text-align: right; font-weight: bold;">5 (25%)</td>
                </tr>
                <tr style="background: #f8f9fa;">
                    <td><strong>Total</strong></td>
                    <td style="text-align: right; font-weight: bold;">20 casos</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>📊 Resumo por Doença</h2>
        </div>
        
        <table class="table" style="font-size: 0.95em;">
            <tbody>
                <tr>
                    <td>Malaria</td>
                    <td style="text-align: right; font-weight: bold;">8 (40%)</td>
                </tr>
                <tr>
                    <td>Febre Amarela</td>
                    <td style="text-align: right; font-weight: bold;">5 (25%)</td>
                </tr>
                <tr>
                    <td>Tuberculose</td>
                    <td style="text-align: right; font-weight: bold;">4 (20%)</td>
                </tr>
                <tr>
                    <td>Sarampo</td>
                    <td style="text-align: right; font-weight: bold;">2 (10%)</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
