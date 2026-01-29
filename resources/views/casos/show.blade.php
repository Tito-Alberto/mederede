@extends('layouts.app')

@section('title', 'Ficha do Paciente - MEDEREDE')

@section('content')
<div class="page-header">
    <h1>📋 Ficha Clínica Completa</h1>
    <div class="breadcrumb">Dashboard > Casos > Ficha do Paciente</div>
</div>

@php
    $isPublic = Auth::user()->role === 'publico';
    $canManageCasos = !$isPublic;
    $nomeCompleto = $caso->paciente_nome ?? '';
    $nomeMascarado = $nomeCompleto !== '' ? (substr($nomeCompleto, 0, 1) . str_repeat('*', max(0, strlen($nomeCompleto) - 2)) . substr($nomeCompleto, -1)) : 'N/A';
    $nomeExibido = $isPublic ? $nomeMascarado : $nomeCompleto;
@endphp

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    <!-- Lado Esquerdo: Dados Completos com Mascaramento -->
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2>📋 Dados do Paciente (Com Mascaramento)</h2>
            <div>
                @if ($canManageCasos)
                    <a href="/casos/{{ $caso->id }}/edit" class="btn btn-secondary">✏️ Editar</a>
                @endif
                <a href="/casos" class="btn btn-secondary">← Voltar</a>
            </div>
        </div>

        <div style="padding: 20px;">
            <!-- Seção 1: Informações Pessoais -->
            <div style="margin-bottom: 25px; padding: 15px; background: #f8f9fa; border-radius: 8px; border-left: 4px solid #6c5ce7;">
                <h3 style="color: #6c5ce7; margin-bottom: 15px;">👤 Informações Pessoais</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Nome Completo:</p>
                        <p style="margin: 5px 0 0 0; font-size: 1.1em;">
                            <span style="font-weight: bold; letter-spacing: 1px;">
                                {{ $nomeMascarado }}
                            </span>
                            @if (!$isPublic)
                                <br><small style="color: #999; font-weight: normal; letter-spacing: 0;">{{ $nomeCompleto }}</small>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Bilhete de Identidade:</p>
                        <p style="margin: 5px 0 0 0; font-size: 1.1em;">
                            @if($caso->bilhete)
                                <span style="font-weight: bold; letter-spacing: 2px;">
                                    {{ substr($caso->bilhete, 0, 3) }}****{{ substr($caso->bilhete, -2) }}
                                </span>
                                <br><small style="color: #999; font-weight: normal; letter-spacing: 1px;">{{ $caso->bilhete }}</small>
                            @else
                                <span style="color: #999;">Não registado</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Data de Nascimento:</p>
                        <p style="margin: 5px 0 0 0; font-size: 1.1em;">
                            @if($caso->data_nascimento)
                                <span style="font-weight: bold;">
                                    {{ $caso->data_nascimento->format('d/m/Y') }}
                                </span>
                                <br><small style="color: #999; font-weight: normal;">{{ $caso->data_nascimento->format('d') }}/***/{{ $caso->data_nascimento->format('Y') }}</small>
                            @else
                                <span style="color: #999;">Não registada</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Idade Calculada:</p>
                        <p style="margin: 5px 0 0 0; font-size: 1.1em;">
                            @if($caso->data_nascimento)
                                <span style="font-weight: bold;">
                                    {{ \Carbon\Carbon::parse($caso->data_nascimento)->age }} anos
                                </span>
                                <br><small style="color: #999; font-weight: normal;">Calculada da data de nascimento</small>
                            @else
                                <span style="color: #999;">Não disponível</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Seção 2: Informações Clínicas -->
            <div style="margin-bottom: 25px; padding: 15px; background: #f0f4ff; border-radius: 8px; border-left: 4px solid #667eea;">
                <h3 style="color: #667eea; margin-bottom: 15px;">🦠 Informações Clínicas</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Doença Suspeita/Confirmada:</p>
                        <p style="margin: 5px 0 0 0; font-size: 1.1em; font-weight: bold; color: #667eea;">
                            {{ $caso->doenca->nome ?? 'N/A' }}
                            <br><small style="color: #999; font-weight: normal; font-size: 0.9em;">{{ $caso->doenca->codigo ?? 'N/A' }}</small>
                        </p>
                    </div>
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Status do Caso:</p>
                        <p style="margin: 5px 0 0 0; font-size: 1.1em;">
                            @if($caso->status === 'suspeito')
                                <span class="badge badge-warning" style="padding: 8px 12px; font-size: 1em;">🟡 Suspeito</span>
                            @elseif($caso->status === 'confirmado')
                                <span class="badge badge-danger" style="padding: 8px 12px; font-size: 1em;">🔴 Confirmado</span>
                            @else
                                <span class="badge badge-success" style="padding: 8px 12px; font-size: 1em;">🟢 Descartado</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div style="margin-top: 15px;">
                    <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Data de Início dos Sintomas:</p>
                    <p style="margin: 5px 0 0 0; font-size: 1.1em; font-weight: bold;">
                        {{ \Carbon\Carbon::parse($caso->data_inicio)->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <!-- Seção 3: Localização -->
            <div style="margin-bottom: 25px; padding: 15px; background: #fef5e7; border-radius: 8px; border-left: 4px solid #f39c12;">
                <h3 style="color: #f39c12; margin-bottom: 15px;">📍 Localização Geográfica</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Localização:</p>
                        <p style="margin: 5px 0 0 0; font-size: 1.1em; font-weight: bold;">{{ $caso->localizacao }}</p>
                    </div>
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Coordenadas GPS:</p>
                        <p style="margin: 5px 0 0 0; font-size: 0.95em;">
                            Lat: {{ number_format($caso->latitude, 6) }}<br>
                            Lon: {{ number_format($caso->longitude, 6) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Seção 4: Sintomas -->
            <div style="margin-bottom: 25px; padding: 15px; background: #fdebed; border-radius: 8px; border-left: 4px solid #e74c3c;">
                <h3 style="color: #e74c3c; margin-bottom: 15px;">🔬 Sintomas Registados</h3>
                <div style="background: white; padding: 12px; border-radius: 4px; border-left: 3px solid #e74c3c; line-height: 1.6;">
                    {{ $caso->sintomas ?? 'Nenhum sintoma registado' }}
                </div>
            </div>

            <!-- Seção 5: Auditoria -->
            <div style="padding: 15px; background: #ebf5fb; border-radius: 8px; border-left: 4px solid #3498db;">
                <h3 style="color: #3498db; margin-bottom: 15px;">📊 Rastreabilidade</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Registado por:</p>
                        <p style="margin: 5px 0 0 0; font-size: 1em;">{{ $caso->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Email:</p>
                        <p style="margin: 5px 0 0 0; font-size: 0.95em;">{{ $caso->user->email ?? 'N/A' }}</p>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px;">
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Criado em:</p>
                        <p style="margin: 5px 0 0 0; font-size: 0.95em;">{{ \Carbon\Carbon::parse($caso->created_at)->format('d/m/Y H:i:s') }}</p>
                    </div>
                    <div>
                        <p style="margin: 0; font-weight: bold; color: #333; font-size: 0.9em;">Atualizado em:</p>
                        <p style="margin: 5px 0 0 0; font-size: 0.95em;">{{ \Carbon\Carbon::parse($caso->updated_at)->format('d/m/Y H:i:s') }}</p>
                    </div>
                </div>
            </div>

            <div style="margin-top: 30px; text-align: right;">
                @if ($canManageCasos)
                    <a href="/casos/{{ $caso->id }}/edit" class="btn btn-secondary" style="padding: 8px 15px;">✏️ Editar</a>
                @endif
                <a href="/casos" class="btn btn-secondary" style="padding: 8px 15px;">← Voltar</a>
            </div>
        </div>
    </div>

    <!-- Lado Direito: Código QR -->
    <div class="card">
        <div class="card-header">
            <h2>🔐 Código QR Único</h2>
        </div>

        <div style="padding: 20px; text-align: center;">
            <div style="background: white; padding: 15px; border-radius: 8px; display: inline-block; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                {!! $qrCodeSvg !!}
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px; border: 2px solid #6c5ce7;">
                <h4 style="color: #6c5ce7; margin-bottom: 10px; margin-top: 0;">📱 Dados Codificados</h4>
                <div style="text-align: left; background: white; padding: 12px; border-radius: 4px; border-left: 4px solid #6c5ce7; font-size: 0.9em; line-height: 1.8;">
                    <p style="margin: 5px 0;">
                        <strong>Nome:</strong><br>
                        <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">{{ $nomeExibido }}</code>
                    </p>
                    <p style="margin: 10px 0 5px 0;">
                        <strong>Bilhete ID:</strong><br>
                        <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">{{ $caso->bilhete ?? 'N/A' }}</code>
                    </p>
                    <p style="margin: 10px 0 5px 0;">
                        <strong>Nascimento:</strong><br>
                        <code style="background: #f5f5f5; padding: 2px 6px; border-radius: 3px;">
                            {{ $caso->data_nascimento ? $caso->data_nascimento->format('d/m/Y') : 'N/A' }}
                        </code>
                    </p>
                </div>
            </div>

            <div style="margin-top: 20px; padding: 12px; background: #fff3cd; border-radius: 8px; border-left: 4px solid #ffc107; text-align: left;">
                <p style="margin: 0; font-size: 0.85em; color: #856404; line-height: 1.6;">
                    <strong>💡 Instrução:</strong> Escaneie este código QR com um smartphone para validar a identidade do paciente de forma segura e única.
                </p>
            </div>

            @if ($canManageCasos)
                <div style="margin-top: 20px;">
                    <a href="{{ route('casos.print', $caso->id) }}" target="_blank" class="btn btn-primary" style="padding: 8px 15px;">🖨️ Imprimir Ficha</a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
