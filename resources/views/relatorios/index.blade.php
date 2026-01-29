@extends('layouts.app')

@section('title', 'Relatorios - MEDEREDE')

@section('content')
<div class="page-header">
    <h1>Relatorios</h1>
    <div class="breadcrumb">Dashboard > Relatorios</div>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Gerar Relatorio</h2>
    </div>

    <form action="/relatorios" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label for="tipo">Tipo de Saida</label>
                <select id="tipo" name="tipo" class="form-control" required>
                    <option value="PDF">PDF</option>
                    <option value="CSV">CSV</option>
                </select>
            </div>
            <div class="form-group">
                <label for="data_inicio">Data Inicio</label>
                <input id="data_inicio" name="data_inicio" type="date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="data_fim">Data Fim</label>
                <input id="data_fim" name="data_fim" type="date" class="form-control" required>
            </div>
        </div>
        <input type="hidden" name="periodo" value="custom">

        <div class="card" style="background: #f8f9fa; border-left: 4px solid #667eea; margin: 20px 0;">
            <h3 style="color: #667eea; margin-bottom: 15px;">Conteudo do Relatorio</h3>
            <div class="form-group" style="margin-bottom: 0;">
                <label for="conteudo">Escolha o conteudo</label>
                <select id="conteudo" name="sections" class="form-control" required>
                    <option value="" selected disabled>Escolha o tipo de relatorio</option>
                    <option value="all">Todos</option>
                    <option value="casos">Casos cadastrados</option>
                    <option value="alertas">Alertas</option>
                    <option value="evolucao">Evolucao temporal</option>
                    <option value="distribuicao_doencas">Distribuicao de doencas</option>
                    <option value="casos_status">Casos por status</option>
                    <option value="alertas_recentes">Alertas recentes</option>
                    <option value="casos_recentes">Casos recentes</option>
                    <option value="dados_geograficos">Dados geograficos</option>
                    <option value="resumo_doencas">Resumo por doencas</option>
                </select>
                @error('sections')
                    <div style="color: #dc2626; font-size: 0.9em; margin-top: 6px;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Gerar Relatorio</button>
            <button type="submit" name="preview" value="1" class="btn btn-secondary">Preview</button>
        </div>
    </form>
</div>

@endsection
