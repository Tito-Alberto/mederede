@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-qrcode"></i> QR Code de Validação
                    </h3>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <h5>Usuário: <strong>{{ $user->name }}</strong></h5>
                        <p class="text-muted">Bilhete: {{ $user->bilhete }}</p>
                    </div>

                    <!-- QR Code Image -->
                    <div class="mb-4 p-4 bg-light rounded border">
                        {!! $qrCode->getDataUri() !!}
                    </div>

                    <!-- Dados Codificados -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Dados Codificados</h6>
                        <small class="d-block text-muted" style="word-break: break-all;">
                            {{ $qrData }}
                        </small>
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
                        </div>
                    </div>

                    <!-- Ações -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="{{ route('qrcode.download', $user) }}" class="btn btn-success btn-block w-100">
                                <i class="fas fa-download"></i> Baixar QR Code
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('qrcode.list') }}" class="btn btn-secondary btn-block w-100">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </div>

                    <!-- Nota -->
                    <div class="alert alert-warning mt-4" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <strong>Nota Importante:</strong>
                        <p class="mb-0">Este QR Code contém dados de identificação. Use apenas para fins de validação autorizada.</p>
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
        border: none;
        border-radius: 10px;
    }

    .card-header {
        border-radius: 10px 10px 0 0;
    }
</style>
@endsection
