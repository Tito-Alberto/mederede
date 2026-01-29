@extends('layouts.app')

@section('title', 'Registar Novo Caso - MEDEREDE')

@section('content')
<div class="page-header">
    <h1>📝 Registar Novo Caso</h1>
    <div class="breadcrumb">Dashboard > Casos > Novo Caso</div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Preencha os Dados do Caso</h2>
    </div>

    <form method="POST" action="/casos">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label for="paciente_nome">👤 Nome do Paciente</label>
                <input type="text" id="paciente_nome" name="paciente_nome" class="form-control" placeholder="Ex: João Silva" required>
            </div>

            <div class="form-group">
                <label for="bilhete">📋 Bilhete/ID</label>
                <input type="text" id="bilhete" name="bilhete" class="form-control" placeholder="Ex: CC12345678" value="{{ old('bilhete') }}">
            </div>
        </div>

        <div class="form-row">
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
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="data_inicio">📅 Data de Início dos Sintomas</label>
                <input type="date" id="data_inicio" name="data_inicio" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="localizacao">📍 Localização</label>
                <input type="text" id="localizacao" name="localizacao" class="form-control" placeholder="Ex: Matala" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="latitude">🧭 Latitude</label>
                <input type="number" id="latitude" name="latitude" class="form-control" placeholder="Ex: 38.7223" step="0.0001" min="-90" max="90">
            </div>

            <div class="form-group">
                <label for="longitude">🧭 Longitude</label>
                <input type="number" id="longitude" name="longitude" class="form-control" placeholder="Ex: -9.1393" step="0.0001" min="-180" max="180">
            </div>
        </div>

        <div class="form-group">
            <label for="sintomas">🤒 Sintomas Apresentados</label>
            <textarea id="sintomas" name="sintomas" class="form-control" placeholder="Descreva os sintomas observados..." required></textarea>
        </div>

        <div class="card" style="background: #f8f9fa; border-left: 4px solid #667eea; margin: 20px 0;">
            <h3 style="color: #667eea; margin-bottom: 15px;">💡 Dicas para Preenchimento</h3>
            <ul style="margin-left: 20px; color: #666; line-height: 1.8;">
                <li><strong>Nome:</strong> Nome completo do paciente</li>
                <li><strong>Data de Nascimento:</strong> Deixar em branco se desconhecida</li>
                <li><strong>Doença:</strong> Baseado na suspeita ou confirmação diagnóstica</li>
                <li><strong>Status:</strong> Suspeito = possível, Confirmado = diagnóstico confirmado, Descartado = descartado</li>
                <li><strong>Coordenadas:</strong> Opcionais - Use para visualização em mapas</li>
                <li><strong>Sintomas:</strong> Listar todos os sintomas observados e a duração aproximada</li>
            </ul>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-success">✅ Registar Caso</button>
            <a href="/dashboard" class="btn btn-secondary">❌ Cancelar</a>
        </div>
    </form>
</div>

<script>
    // Auto-popular data com data de hoje se não preenchida
    document.getElementById('data_inicio').valueAsDate = new Date();
</script>
@endsection
