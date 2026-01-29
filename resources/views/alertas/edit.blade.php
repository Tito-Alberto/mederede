@extends('layouts.app')

@section('title', 'Editar Alerta - MEDEREDE')

@section('content')
<div class="page-header">
    <h1>Editar Alerta</h1>
    <div class="breadcrumb">Dashboard > Alertas > Editar</div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Atualizar Dados do Alerta</h2>
    </div>

    <form method="POST" action="{{ route('alertas.update', $alerta) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="caso_id">Selecionar Caso</label>
            <select id="caso_id" name="caso_id" class="form-control" required>
                <option value="">-- Selecionar Caso --</option>
                @foreach($casos as $caso)
                    <option value="{{ $caso->id }}" @selected($alerta->caso_id == $caso->id)>
                        Caso #{{ str_pad($caso->id, 3, '0', STR_PAD_LEFT) }} - {{ $caso->paciente_nome }} ({{ $caso->doenca?->nome ?? 'N/A' }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="titulo">Titulo do Alerta</label>
                <input type="text" id="titulo" name="titulo" class="form-control" value="{{ old('titulo', $alerta->titulo) }}" required>
            </div>

            <div class="form-group">
                <label for="tipo">Tipo de Alerta</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    <option value="email" @selected($alerta->tipo === 'email')>Email</option>
                    <option value="sms" @selected($alerta->tipo === 'sms')>SMS</option>
                    <option value="notificacao" @selected($alerta->tipo === 'notificacao')>Notificacao</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="mensagem">Mensagem do Alerta</label>
            <textarea id="mensagem" name="mensagem" class="form-control" required>{{ old('mensagem', $alerta->mensagem) }}</textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="data_alerta">Data e Hora do Alerta</label>
                <input type="datetime-local" id="data_alerta" name="data_alerta" class="form-control"
                       value="{{ old('data_alerta', optional($alerta->data_alerta)->format('Y-m-d\\TH:i')) }}" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="pendente" @selected($alerta->status === 'pendente')>Pendente</option>
                    <option value="enviado" @selected($alerta->status === 'enviado')>Enviado</option>
                    <option value="falhou" @selected($alerta->status === 'falhou')>Falhou</option>
                </select>
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-success">Atualizar Alerta</button>
            <a href="/alertas" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
