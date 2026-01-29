@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus"></i> Criar Novo Utilizador
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-exclamation-circle"></i> Erros encontrados:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="fas fa-user"></i> Nome Completo <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" placeholder="Ex: João Silva" 
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" placeholder="exemplo@email.com" 
                                   value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock"></i> Senha <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">A senha deve ter pelo menos 8 caracteres.</small>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                <i class="fas fa-lock"></i> Confirmar Senha <span class="text-danger">*</span>
                            </label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirme a senha" required>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">
                                <i class="fas fa-shield-alt"></i> Função <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                <option value="">-- Selecione uma função --</option>
                                <option value="admin" @selected(old('role') === 'admin')>
                                    <i class="fas fa-crown"></i> Administrador
                                </option>
                                <option value="profissional_saude" @selected(old('role') === 'profissional_saude')>
                                    <i class="fas fa-stethoscope"></i> Profissional de Saúde
                                </option>
                                <option value="publico" @selected(old('role') === 'publico')>
                                    <i class="fas fa-user"></i> Público
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bilhete" class="form-label">
                                    <i class="fas fa-id-card"></i> Bilhete de Identidade
                                </label>
                                <input type="text" class="form-control @error('bilhete') is-invalid @enderror" 
                                       id="bilhete" name="bilhete" placeholder="Ex: 12345678" 
                                       value="{{ old('bilhete') }}">
                                @error('bilhete')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="data_nascimento" class="form-label">
                                    <i class="fas fa-birthday-cake"></i> Data de Nascimento
                                </label>
                                <input type="date" class="form-control @error('data_nascimento') is-invalid @enderror" 
                                       id="data_nascimento" name="data_nascimento" 
                                       value="{{ old('data_nascimento') }}">
                                @error('data_nascimento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="telefone" class="form-label">
                                <i class="fas fa-phone"></i> Telefone
                            </label>
                            <input type="tel" class="form-control @error('telefone') is-invalid @enderror" 
                                   id="telefone" name="telefone" placeholder="Ex: +258 82 123 4567" 
                                   value="{{ old('telefone') }}">
                            @error('telefone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="endereco" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> Endereço
                            </label>
                            <textarea class="form-control @error('endereco') is-invalid @enderror" 
                                      id="endereco" name="endereco" rows="2" 
                                      placeholder="Ex: Rua Principal, Nº 123, Cidade">{{ old('endereco') }}</textarea>
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle"></i> <strong>Informações sobre as funções:</strong>
                            <ul class="mb-0 mt-2 small">
                                <li><strong>Administrador:</strong> Acesso total ao sistema, pode criar e gerenciar utilizadores, ver todos os dados.</li>
                                <li><strong>Profissional de Saúde:</strong> Pode registar casos, criar alertas, ver dados de casos que registou.</li>
                                <li><strong>Público:</strong> Acesso limitado, apenas para consultar informações públicas.</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Criar Utilizador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Form validation
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection
