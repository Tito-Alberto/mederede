@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-user-circle"></i> Detalhes do Utilizador
                            </h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('users.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <div class="avatar-circle mb-3">
                                <div class="bg-primary text-white rounded-circle p-5 d-inline-flex align-items-center justify-content-center"
                                     style="width: 150px; height: 150px; font-size: 60px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <h6 class="text-muted">ID: {{ $user->id }}</h6>
                        </div>

                        <div class="col-md-9">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h4 class="mb-2">{{ $user->name }}</h4>
                                    @endif

                                    <div class="mb-3">
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-danger" style="font-size: 14px;">
                                                <i class="fas fa-crown"></i> Administrador
                                            </span>
                                        @elseif ($user->role === 'profissional_saude')
                                            <span class="badge bg-info" style="font-size: 14px;">
                                                <i class="fas fa-stethoscope"></i> Profissional de Saúde
                                            </span>
                                        @else
                                            <span class="badge bg-secondary" style="font-size: 14px;">
                                                <i class="fas fa-user"></i> Público
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small"><i class="fas fa-envelope"></i> Email</label>
                                    <p class="mb-0"><strong>{{ $user->email }}</strong></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small"><i class="fas fa-id-card"></i> Bilhete de Identidade</label>
                                    <p class="mb-0"><strong>{{ $user->bilhete ?? 'Não informado' }}</strong></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small"><i class="fas fa-birthday-cake"></i> Data de Nascimento</label>
                                    <p class="mb-0"><strong>{{ $user->data_nascimento ? \Carbon\Carbon::parse($user->data_nascimento)->format('d/m/Y') : 'Não informada' }}</strong></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small"><i class="fas fa-phone"></i> Telefone</label>
                                    <p class="mb-0"><strong>{{ $user->telefone ?? 'Não informado' }}</strong></p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="text-muted small"><i class="fas fa-map-marker-alt"></i> Endereço</label>
                                    <p class="mb-0"><strong>{{ $user->endereco ?? 'Não informado' }}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="fas fa-clock"></i> Histórico de Conta</h6>
                            <ul class="list-unstyled small">
                                <li class="mb-2">
                                    <strong>Conta criada:</strong>
                                    <span class="text-muted">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Última atualização:</strong>
                                    <span class="text-muted">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                                </li>
                                <li class="mb-2">
                                    <strong>Último login:</strong>
                                    @if($user->last_login_at)
                                        <span class="text-muted">{{ $user->last_login_at->format('d/m/Y H:i') }}</span>
                                    @else
                                        <span class="badge bg-warning">Nunca fez login</span>
                                    @endif
                                </li>

                            </ul>
                        </div>

                        <div class="col-md-6">
                            <h6 class="text-primary mb-3"><i class="fas fa-shield-alt"></i> Permissões</h6>
                            <ul class="list-unstyled small">
                                @if ($user->role === 'admin')
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Criar e gerenciar utilizadores</li>
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Registar novos casos</li>
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Criar alertas</li>
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Ver todos os dados</li>
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Gerar relatórios</li>
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Acessar dashboard</li>
                                @elseif ($user->role === 'profissional_saude')
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Registar novos casos</li>
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Criar alertas</li>
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Ver dados dos seus casos</li>
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Gerar relatórios</li>
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Acessar dashboard</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger"></i> Criar utilizadores</li>
                                @else
                                    <li class="mb-2"><i class="fas fa-check text-success"></i> Consultar informações públicas</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger"></i> Registar casos</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger"></i> Criar alertas</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger"></i> Ver dados privados</li>
                                    <li class="mb-2"><i class="fas fa-times text-danger"></i> Criar utilizadores</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <hr>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        @if ($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Tem certeza que deseja deletar este utilizador? Esta ação é permanente.')">
                                    <i class="fas fa-trash"></i> Deletar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
