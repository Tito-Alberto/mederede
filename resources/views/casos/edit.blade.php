@extends('layouts.app')

@section('title', 'Editar Caso - MEDEREDE')

@section('content')
<div class="page-header">
    <h1>✏️ Editar Caso</h1>
    <div class="breadcrumb">Dashboard > Casos > {{ $caso->paciente_nome }} > Editar</div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Actualizar Dados do Caso</h2>
    </div>

    <form method="POST" action="/casos/{{ $caso->id }}">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-group">
                <label for="paciente_nome">👤 Nome do Paciente</label>
                <input type="text" id="paciente_nome" name="paciente_nome" class="form-control" value="{{ $caso->paciente_nome }}" required>
                @error('paciente_nome')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="bilhete">📋 Bilhete/ID</label>
                <input type="text" id="bilhete" name="bilhete" class="form-control" value="{{ $caso->bilhete }}">
                @error('bilhete')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="data_nascimento">📅 Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" value="{{ $caso->data_nascimento ? $caso->data_nascimento->format('Y-m-d') : '' }}">
                @error('data_nascimento')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="doenca_id">🦠 Doença</label>
                <select id="doenca_id" name="doenca_id" class="form-control" required>
                    <option value="">-- Selecionar Doença --</option>
                    @foreach($doencas as $doenca)
                        <option value="{{ $doenca->id }}" @selected($caso->doenca_id === $doenca->id)>{{ $doenca->nome }}</option>
                    @endforeach
                </select>
                @error('doenca_id')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="status">📊 Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="suspeito" @selected($caso->status === 'suspeito')>🟡 Suspeito</option>
                    <option value="confirmado" @selected($caso->status === 'confirmado')>🔴 Confirmado</option>
                    <option value="descartado" @selected($caso->status === 'descartado')>🟢 Descartado</option>
                </select>
                @error('status')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="data_inicio">📅 Data de Início</label>
                <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="{{ $caso->data_inicio->format('Y-m-d') }}" required>
                @error('data_inicio')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="localizacao">📍 Localização</label>
                <input type="text" id="localizacao" name="localizacao" class="form-control" value="{{ $caso->localizacao }}" placeholder="Ex: Matala" required>
                @error('localizacao')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="latitude">🗺️ Latitude</label>
                <input type="number" id="latitude" name="latitude" class="form-control" step="0.00000001" value="{{ $caso->latitude }}" required>
                @error('latitude')<span class="text-danger">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="longitude">🗺️ Longitude</label>
                <input type="number" id="longitude" name="longitude" class="form-control" step="0.00000001" value="{{ $caso->longitude }}" required>
                @error('longitude')<span class="text-danger">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-group">
            <label for="sintomas">🔬 Sintomas</label>
            <textarea id="sintomas" name="sintomas" class="form-control" rows="4" placeholder="Descreva os sintomas observados...">{{ $caso->sintomas }}</textarea>
            @error('sintomas')<span class="text-danger">{{ $message }}</span>@enderror
        </div>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">💾 Guardar Alterações</button>
            <a href="/casos/{{ $caso->id }}" class="btn btn-secondary">← Ver Caso</a>
            <a href="/casos" class="btn btn-secondary">← Voltar à Lista</a>
        </div>
    </form>
</div>

@endsection
