@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-success">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-check-circle"></i> Validação Bem-sucedida!
                    </h3>
                </div>
                <div class="card-body text-center">
                    <div class="alert alert-success" role="alert">
                        <h5>{{ $message }}</h5>
                    </div>

                    <div class="patient-card mt-4">
                        <h5 class="section-title">Dados do Paciente</h5>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="label">Nome Completo</div>
                                <div class="value">{{ $user->name ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">Bilhete</div>
                                <div class="value">{{ $user->bilhete ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">Data de Nascimento</div>
                                <div class="value">{{ $user->data_nascimento ? \Carbon\Carbon::parse($user->data_nascimento)->format('d/m/Y') : 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">Email</div>
                                <div class="value">{{ $user->email ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="label">Funcao</div>
                                <div class="value">{{ ucfirst($user->role ?? 'Usuario') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Status de Validação -->
                    <div class="alert alert-success mt-4">
                        <i class="fas fa-shield-alt"></i> <strong>Status:</strong> Identidade validada com sucesso
                    </div>

                    <!-- Ações -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="{{ route('qrcode.validate') }}" class="btn btn-primary btn-block w-100">
                                <i class="fas fa-redo"></i> Validar Outro
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('qrcode.list') }}" class="btn btn-secondary btn-block w-100">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </div>

                    <!-- Nota de Segurança -->
                    <div class="alert alert-info mt-4" role="alert">
                        <i class="fas fa-lock"></i> <strong>Informação de Segurança:</strong>
                        <p class="mb-0">Os dados foram validados com sucesso contra a base de dados. Esta validação confirma que a identidade é legítima.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .patient-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 16px;
        text-align: left;
    }

    .section-title {
        font-weight: 600;
        margin-bottom: 12px;
        color: #333;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
    }

    .info-item {
        background: #ffffff;
        border: 1px solid #eef0f2;
        border-radius: 8px;
        padding: 12px;
    }

    .info-item .label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 4px;
    }

    .info-item .value {
        font-weight: 600;
        color: #111827;
        word-break: break-word;
    }

    .btn-block {
        display: block;
        width: 100%;
    }

    .card {
        border-radius: 10px;
    }

    .card-header {
        border-radius: 10px 10px 0 0;
    }

    table {
        margin-bottom: 0;
    }

    th {
        background-color: #f8f9fa;
    }
</style>
@endsection
