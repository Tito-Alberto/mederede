<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha do Paciente - MEDEREDE</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #222;
            margin: 24px;
        }
        h1 {
            margin: 0 0 6px 0;
            font-size: 22px;
        }
        .sub {
            color: #666;
            font-size: 12px;
            margin-bottom: 16px;
        }
        .section {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 12px;
        }
        .section h2 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 24px;
        }
        .label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }
        .value {
            font-size: 14px;
            margin-top: 2px;
        }
        .actions {
            margin-top: 16px;
        }
        .btn {
            padding: 8px 14px;
            background: #2563eb;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            font-size: 13px;
            display: inline-block;
        }
        @media print {
            .actions {
                display: none;
            }
            body {
                margin: 10mm;
            }
        }
    </style>
</head>
<body>
    <h1>Ficha Clinica do Paciente</h1>
    <div class="sub">MEDEREDE - Sistema de Monitorizacao de Doencas</div>

    <div class="section">
        <h2>Informacoes Pessoais</h2>
        <div class="grid">
            <div>
                <div class="label">Nome completo</div>
                <div class="value">{{ $caso->paciente_nome }}</div>
            </div>
            <div>
                <div class="label">Bilhete/ID</div>
                <div class="value">{{ $caso->bilhete ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="label">Data de nascimento</div>
                <div class="value">
                    {{ $caso->data_nascimento ? $caso->data_nascimento->format('d/m/Y') : 'N/A' }}
                </div>
            </div>
            <div>
                <div class="label">Idade</div>
                <div class="value">
                    {{ $caso->data_nascimento ? \Carbon\Carbon::parse($caso->data_nascimento)->age . ' anos' : 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Informacoes Clinicas</h2>
        <div class="grid">
            <div>
                <div class="label">Doenca</div>
                <div class="value">{{ $caso->doenca->nome ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="label">Codigo</div>
                <div class="value">{{ $caso->doenca->codigo ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="label">Status</div>
                <div class="value">{{ ucfirst($caso->status) }}</div>
            </div>
            <div>
                <div class="label">Data de inicio dos sintomas</div>
                <div class="value">{{ \Carbon\Carbon::parse($caso->data_inicio)->format('d/m/Y') }}</div>
            </div>
        </div>
        <div style="margin-top: 10px;">
            <div class="label">Sintomas</div>
            <div class="value">{{ $caso->sintomas ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="section">
        <h2>Localizacao</h2>
        <div class="grid">
            <div>
                <div class="label">Provincia</div>
                <div class="value">{{ $caso->provincia ?: 'N/A' }}</div>
            </div>
            <div>
                <div class="label">Municipio</div>
                <div class="value">{{ $caso->municipio ?: 'N/A' }}</div>
            </div>
            <div>
                <div class="label">Coordenadas</div>
                <div class="value">
                    Lat: {{ number_format($caso->latitude, 6) }} | Lon: {{ number_format($caso->longitude, 6) }}
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Rastreabilidade</h2>
        <div class="grid">
            <div>
                <div class="label">Registado por</div>
                <div class="value">{{ $caso->user->name ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="label">Email</div>
                <div class="value">{{ $caso->user->email ?? 'N/A' }}</div>
            </div>
            <div>
                <div class="label">Criado em</div>
                <div class="value">{{ \Carbon\Carbon::parse($caso->created_at)->format('d/m/Y H:i') }}</div>
            </div>
            <div>
                <div class="label">Atualizado em</div>
                <div class="value">{{ \Carbon\Carbon::parse($caso->updated_at)->format('d/m/Y H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="actions">
        <a class="btn" href="javascript:window.print()">Imprimir / Guardar PDF</a>
    </div>
</body>
</html>
