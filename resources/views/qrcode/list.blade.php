@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-qrcode"></i> Gerenciador de QR Codes
                    </h3>
                </div>
                <div class="card-body">
                    @if($users->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nenhum usuário disponível
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Bilhete</th>
                                        <th>Data Nascimento</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{ $user->name ?? 'N/A' }}</td>
                                            <td>
                                                @if($user->bilhete)
                                                    <span class="badge bg-success">{{ $user->bilhete }}</span>
                                                @else
                                                    <span class="badge bg-danger">Incompleto</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->data_nascimento)
                                                    {{ \Carbon\Carbon::parse($user->data_nascimento)->format('d/m/Y') }}
                                                @else
                                                    <span class="badge bg-danger">Falta</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->bilhete && $user->data_nascimento && $user->name)
                                                    <a href="{{ route('qrcode.generate', $user) }}" class="btn btn-sm btn-primary" title="Gerar QR Code">
                                                        <i class="fas fa-qrcode"></i>
                                                    </a>
                                                    <a href="{{ route('qrcode.download', $user) }}" class="btn btn-sm btn-success" title="Baixar QR Code">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled title="Dados incompletos">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} usuários
                            </div>
                            {{ $users->links() }}
                        </div>
                    @endif

                    <hr>

                    <div class="alert alert-info mt-4">
                        <h5><i class="fas fa-info-circle"></i> Informação</h5>
                        <p>O QR Code contém os dados únicos de validação:</p>
                        <ul class="mb-0">
                            <li><strong>Bilhete:</strong> Número único de identificação</li>
                            <li><strong>Data de Nascimento:</strong> Data no formato YYYY-MM-DD</li>
                            <li><strong>Nome:</strong> Nome completo do usuário</li>
                        </ul>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <a href="{{ route('qrcode.validate') }}" class="btn btn-warning btn-block w-100">
                                <i class="fas fa-check-circle"></i> Validar QR Code
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-block w-100">
                                <i class="fas fa-arrow-left"></i> Voltar ao Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-block {
        display: block;
        width: 100%;
    }

    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }

    .badge {
        padding: 0.5em 0.75em;
    }
</style>
@endsection
