<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MEDEREDE') - Sistema de Monitorização de Doenças</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f7fa;
            color: #333;
        }

        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 60px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-size: 1.3em;
            font-weight: bold;
            text-decoration: none;
            color: white;
        }

        .navbar-menu {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .navbar-menu a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }

        .navbar-menu a:hover {
            opacity: 0.8;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar {
            position: fixed;
            left: 0;
            top: 60px;
            width: 250px;
            height: calc(100vh - 60px);
            background: white;
            border-right: 1px solid #e9ecef;
            overflow-y: auto;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li {
            border-bottom: 1px solid #e9ecef;
        }

        .sidebar-menu a {
            display: block;
            padding: 15px 20px;
            color: #333;
            text-decoration: none;
            transition: background 0.3s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #f0f0f0;
            color: #667eea;
            font-weight: bold;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 30px;
            min-height: calc(100vh - 60px);
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 2em;
            color: #333;
            margin-bottom: 10px;
        }

        .breadcrumb {
            color: #999;
            font-size: 0.9em;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .card-header {
            border-bottom: 2px solid #f0f0f0;
            margin-bottom: 20px;
            padding-bottom: 15px;
        }

        .card-header h2 {
            color: #667eea;
            font-size: 1.3em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
            border: 1px solid #ddd;
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: bold;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e9ecef;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .alert-success {
            background: #d1fae5;
            border-color: #10b981;
            color: #065f46;
        }

        .alert-error {
            background: #fee2e2;
            border-color: #ef4444;
            color: #991b1b;
        }

        .alert-info {
            background: #dbeafe;
            border-color: #3b82f6;
            color: #0c4a6e;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-top: 4px solid #667eea;
        }

        .stat-card .number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }

        .stat-card .label {
            color: #999;
            font-size: 0.9em;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }

            .main-content {
                margin-left: 0;
            }

            .navbar-menu {
                gap: 10px;
                font-size: 0.9em;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="navbar-brand">🏥 MEDEREDE</a>
        <div class="navbar-menu">
            @auth
                
               
                <div class="navbar-user">
                    <span>👤 {{ Auth::user()->name }}</span>
                    <a href="/logout">Sair</a>
                </div>
            @else
                <a href="/login">Login</a>
            @endauth
        </div>
    </nav>

    @auth
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="/dashboard">📊 Dashboard</a></li>
                @if (Auth::user()->role !== 'publico')
                    <li><a href="/casos">📝 Casos</a></li>
                @endif
                @if (Auth::user()->role !== 'publico')
                    <li><a href="/casos/create">➕ Novo Caso</a></li>
                @endif
                @if (Auth::user()->role !== 'publico')
                    <li><a href="/alertas">🚨 Alertas</a></li>
                @endif
                @if (Auth::user()->role !== 'publico')
                    <li><a href="/relatorios">📄 Relatórios</a></li>
                @endif
                @if (Auth::user()->role === 'admin')
                    <li><a href="{{ route('users.index') }}"><i class="fas fa-users-cog"></i> Gestao de Utilizadores</a></li>
                @endif
                <li><a href="/perfil">👤 Meu Perfil</a></li>
                <li><a href="/logout">🚪 Sair</a></li>
            </ul>
        </aside>
    @endauth

    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>
