@extends('layouts.app')

@section('title', 'Meu Perfil - MEDEREDE')

@section('content')
<div class="page-header">
    <h1>👤 Meu Perfil</h1>
    <div class="breadcrumb">Dashboard > Perfil</div>
</div>

@php
    $user = Auth::user();
    $roleLabels = [
        'admin' => 'Administrador (Acesso Total)',
        'profissional_saude' => 'Profissional de Saude',
        'publico' => 'Publico',
    ];
    $roleTitle = $roleLabels[$user->role] ?? ucfirst($user->role);
@endphp

<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
    <div class="card">
        <div class="card-header">
            <h2>Informações Pessoais</h2>
        </div>

        <div style="text-align: center; padding: 20px;">
            <div style="font-size: 4em; margin-bottom: 15px;">👤</div>
            <h3 style="color: #333; margin-bottom: 5px;">{{ $user->name }}</h3>
            <p style="color: #999; margin-bottom: 15px;">{{ $user->email }}</p>
            
            <div style="background: #f0f0f0; padding: 10px; border-radius: 5px; margin: 15px 0;">
                <p style="font-size: 0.9em; color: #666;"><strong>Role:</strong></p>
                <p style="color: #667eea; font-weight: bold; font-size: 1.1em;">🔐 {{ $roleTitle }}</p>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Detalhes da Conta</h2>
        </div>

        <div class="form-group">
            <label>Nome Completo</label>
            <input type="text" class="form-control" value="{{ $user->name }}" disabled>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
        </div>

        <div class="form-group">
            <label>Role / Permissão</label>
            <input type="text" class="form-control" value="{{ $roleTitle }}" disabled>
        </div>

        <div class="form-group">
            <label>Data de Criação</label>
            <input type="text" class="form-control" value="{{ optional($user->created_at)->format('d/m/Y H:i') ?? 'N/A' }}" disabled>
        </div>

        <div class="form-group">
            <label>Último Acesso</label>
            <input type="text" class="form-control" value="{{ optional($user->last_login_at)->format('d/m/Y H:i') ?? 'N/A' }}" disabled>
        </div>
    </div>
</div>

<div style="margin-top: 20px; display: flex; gap: 10px;">
    <a href="/dashboard" class="btn btn-secondary">← Voltar</a>
</div>
@endsection
