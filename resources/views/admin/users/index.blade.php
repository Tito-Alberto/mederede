@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="max-width: 1200px; margin: 0 auto;">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">
                                <i class="fas fa-users"></i> Gerenciar Utilizadores
                            </h5>
                        </div>
                        <div class="col text-end">
                            <a href="{{ route('users.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus"></i> Novo Utilizador
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if ($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Função</th>
                                        <th>Bilhete</th>
                                                                                <th>Data Criação</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <strong>{{ $user->name }}</strong>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->role === 'admin')
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-crown"></i> Administrador
                                                    </span>
                                                @elseif ($user->role === 'profissional_saude')
                                                    <span class="badge bg-info">
                                                        <i class="fas fa-stethoscope"></i> Profissional de Saúde
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-user"></i> Público
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $user->bilhete ?? 'N/A' }}</td>
                                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <div style="display: flex; gap: 8px; align-items: center;">
                                                    @if ($user->role === 'publico')
                                                        <form action="{{ route('users.approve', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="role" value="profissional_saude">
                                                            <button type="submit" class="btn btn-sm btn-info" title="Definir como Profissional de Saude">
                                                                <i class="fas fa-stethoscope"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if ($user->role === 'profissional_saude')
                                                        <form action="{{ route('users.approve', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="role" value="publico">
                                                            <button type="submit" class="btn btn-sm btn-secondary" title="Voltar para PÃºblico"
                                                                    onclick="return confirm('Tem certeza que deseja voltar este utilizador para PÃºblico?')">
                                                                <i class="fas fa-user"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if ($user->id !== auth()->id())
                                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                                    onclick="return confirm('Tem certeza que deseja deletar este utilizador?')" title="Deletar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Paginação --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center py-5">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <p class="mb-0">Nenhum utilizador encontrado.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
