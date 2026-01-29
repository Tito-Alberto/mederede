@extends('layouts.app')

@section('title', 'Novo Alerta - MEDEREDE')

@section('content')
<div class="page-header">
    <h1>🚨 Criar Novo Alerta</h1>
    <div class="breadcrumb">Dashboard > Alertas > Novo Alerta</div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Preencha os Dados do Alerta</h2>
    </div>

    <form method="POST" action="{{ route('alertas.store') }}">
        @csrf

        <div class="form-group">
            <label for="caso_id">📝 Selecionar Caso</label>
            <select id="caso_id" name="caso_id" class="form-control" required>
                <option value="">-- Selecionar Caso --</option>
                @foreach($casos as $caso)
                    <option value="{{ $caso->id }}">
                        Caso #{{ str_pad($caso->id, 3, '0', STR_PAD_LEFT) }} - {{ $caso->paciente_nome }} ({{ $caso->doenca?->nome ?? 'N/A' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="titulo">Título do Alerta</label>
                <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Ex: Alerta de Malaria Confirmada" required>
            </div>

            <div class="form-group">
                <label for="tipo">Tipo de Alerta</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    <option value="email">📧 Email</option>
                    <option value="sms">📱 SMS</option>
                    <option value="notificacao">💬 Notificação</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="mensagem">Mensagem do Alerta</label>
            <textarea id="mensagem" name="mensagem" class="form-control" placeholder="Descreva o conteúdo do alerta..." required></textarea>
        </div>

        <div class="form-group">
            <label for="data_alerta">📅 Data e Hora do Alerta</label>
            <input type="datetime-local" id="data_alerta" name="data_alerta" class="form-control" required>
        </div>

        <div class="card" style="background: #f8f9fa; border-left: 4px solid #667eea; margin: 20px 0;">
            <h3 style="color: #667eea; margin-bottom: 15px;">💡 Dicas para Alertas</h3>
            <ul style="margin-left: 20px; color: #666; line-height: 1.8;">
                <li><strong>Email:</strong> Melhor para comunicações detalhadas e documentação</li>
                <li><strong>SMS:</strong> Apropriado para alertas urgentes e de curta duração</li>
                <li><strong>Notificação:</strong> Ideal para acompanhamento contínuo no sistema</li>
                <li>Personalize a mensagem com informações relevantes do caso</li>
                <li>Defina a data e hora do envio do alerta</li>
            </ul>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-success">✅ Criar Alerta</button>
            <a href="/alertas" class="btn btn-secondary">❌ Cancelar</a>
        </div>
    </form>
</div>

<script>
    // Auto-popular data com data/hora de agora
    const now = new Date();
    const isoDateTime = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
    document.getElementById('data_alerta').value = isoDateTime;
</script>
@endsection
