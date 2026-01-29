@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i> Editar Utilizador: {{ $user->name }}
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

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <ul class="nav nav-tabs mb-3" id="userTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" 
                                    data-bs-target="#info" type="button" role="tab">
                                <i class="fas fa-user"></i> Informações Pessoais
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" 
                                    data-bs-target="#security" type="button" role="tab">
                                <i class="fas fa-lock"></i> Segurança
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="userTabContent">
                        <!-- Aba Informações Pessoais -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <form action="{{ route('users.update', $user) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="name" class="form-label">
                                        <i class="fas fa-user"></i> Nome Completo <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope"></i> Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="role" class="form-label">
                                        <i class="fas fa-shield-alt"></i> Função <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" name="role" required>
                                        <option value="admin" @selected(old('role', $user->role) === 'admin')>
                                            <i class="fas fa-crown"></i> Administrador
                                        </option>
                                        <option value="profissional_saude" @selected(old('role', $user->role) === 'profissional_saude')>
                                            <i class="fas fa-stethoscope"></i> Profissional de Saúde
                                        </option>
                                        <option value="publico" @selected(old('role', $user->role) === 'publico')>
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
                                               id="bilhete" name="bilhete" value="{{ old('bilhete', $user->bilhete) }}">
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
                                               value="{{ old('data_nascimento', $user->data_nascimento) }}">
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
                                           id="telefone" name="telefone" value="{{ old('telefone', $user->telefone) }}">
                                    @error('telefone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="endereco" class="form-label">
                                        <i class="fas fa-map-marker-alt"></i> Endereço
                                    </label>
                                    <textarea class="form-control @error('endereco') is-invalid @enderror" 
                                              id="endereco" name="endereco" rows="2">{{ old('endereco', $user->endereco) }}</textarea>
                                    @error('endereco')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Atualizar
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Aba Segurança -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle"></i> Use esta seção para resetar a senha do utilizador.
                            </div>

                            <form action="{{ route('users.reset-password', $user) }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-info-circle"></i> <strong>Informação:</strong>
                                    </label>
                                    <p class="text-muted">
                                        Uma nova senha temporária será gerada e o utilizador será notificado por email.
                                    </p>
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <button type="submit" class="btn btn-warning" 
                                            onclick="return confirm('Tem certeza? Uma nova senha temporária será enviada por email.')">
                                        <i class="fas fa-key"></i> Gerar Nova Senha
                                    </button>
                                </div>
                            </form>

                            <hr class="my-4">

                            <h6 class="mb-3">Histórico de Segurança</h6>
                            <div class="alert alert-light">
                                <ul class="mb-0 small">
                                    <li><strong>Conta criada:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
                                    <li><strong>Último login:</strong> {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</li>
                                    <li><strong>Última atualização:</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
