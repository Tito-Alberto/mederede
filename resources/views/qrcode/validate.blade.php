@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-warning text-dark">
                    <h3 class="mb-0">
                        <i class="fas fa-check-circle"></i> Validar QR Code
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">
                        Insira os dados codificados no QR Code para validar a identidade.
                    </p>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <strong>Erro!</strong>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('qrcode.check') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="qr_data" class="form-label">
                                <strong>Dados do QR Code</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <textarea 
                                name="qr_data" 
                                id="qr_data" 
                                class="form-control @error('qr_data') is-invalid @enderror"
                                rows="4"
                                placeholder="Cole aqui os dados do QR Code&#10;Formato: bilhete|data_nascimento|nome"
                                required
                            >{{ old('qr_data') }}</textarea>
                            <small class="form-text text-muted d-block mt-2">
                                <strong>Formato esperado:</strong> bilhete|data_nascimento|nome<br>
                                <strong>Exemplo:</strong> 12345678|1990-05-15|João Silva
                            </small>
                            @error('qr_data')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Validar
                            </button>
                            <a href="{{ route('qrcode.list') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </form>

                    <!-- Informações Úteis -->
                    <div class="alert alert-info mt-4" role="alert">
                        <h6><i class="fas fa-lightbulb"></i> Como usar</h6>
                        <ol class="mb-0">
                            <li>Digitalize o QR Code com uma câmera ou leitor QR</li>
                            <li>Copie os dados exibidos</li>
                            <li>Cole os dados no campo acima</li>
                            <li>Clique em "Validar" para verificar a identidade</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    textarea {
        font-family: monospace;
        font-size: 0.9rem;
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
