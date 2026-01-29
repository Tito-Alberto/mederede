<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEDEREDE - Sistema de Monitorização de Doenças Endêmicas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    @vite('resources/css/app.css')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #667eea;
            --primary-dark: #764ba2;
            --secondary: #f093fb;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --light: #f8fafc;
            --dark: #1e293b;
            --text: #475569;
            --border: #e2e8f0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f8fafc;
            color: var(--text);
            line-height: 1.6;
        }

        /* NAVBAR */
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-menu {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .navbar-menu a {
            text-decoration: none;
            color: var(--text);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar-menu a:hover {
            color: var(--primary);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-secondary:hover {
            background: var(--primary);
            color: white;
        }

        /* HERO SECTION */
        .hero {
            margin-top: 60px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 100px 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* MAIN CONTAINER */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* FEATURES SECTION */
        .features-section {
            padding: 60px 2rem;
            background: white;
            margin-top: 40px;
            border-radius: 12px;
        }

        .section-title {
            font-size: 2.5rem;
            margin-bottom: 3rem;
            text-align: center;
            color: var(--dark);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin: 3rem 0;
        }

        .feature-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.15);
            border-color: var(--primary);
        }

        .feature-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1rem;
        }

        .feature-card h3 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .feature-card p {
            color: var(--text);
            font-size: 0.95rem;
        }

        /* STATS SECTION */
        .stats-section {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 60px 2rem;
            margin: 40px 0;
            border-radius: 12px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 0.95rem;
            opacity: 0.9;
        }

        /* CREDENTIALS SECTION */
        .credentials-section {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 12px;
            padding: 2rem;
            margin: 40px 0;
        }

        .credentials-section h3 {
            color: #856404;
            margin-bottom: 1rem;
        }

        .credentials-table {
            width: 100%;
            border-collapse: collapse;
        }

        .credentials-table td {
            padding: 0.75rem;
            border: 1px solid #ffc107;
            color: #856404;
        }

        .credentials-table td:first-child {
            font-weight: 600;
        }

        /* CTA SECTION */
        .cta-section {
            text-align: center;
            padding: 60px 2rem;
        }

        .cta-section h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .cta-section p {
            font-size: 1.1rem;
            color: var(--text);
            margin-bottom: 2rem;
        }

        /* FOOTER */
        .footer {
            background: var(--dark);
            color: white;
            padding: 40px 2rem;
            text-align: center;
            margin-top: 60px;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            text-align: left;
            margin-bottom: 2rem;
        }

        .footer-section h4 {
            margin-bottom: 1rem;
            color: var(--primary);
        }

        .footer-section a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s ease;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer-section a:hover {
            color: var(--primary);
        }

        .footer-bottom {
            border-top: 1px solid #334155;
            padding-top: 2rem;
            text-align: center;
            color: #cbd5e1;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .navbar-menu {
                gap: 1rem;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .hero-buttons .btn {
                width: 100%;
            }
        }

        /* ANIMATIONS */
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Tailwind Custom Styles moved to resources/css/app.css */
    </style>
</head>
<body class="bg-gray-50">
    <!-- NAVBAR -->
    <nav class="sticky top-0 z-50 bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                        <i class="fas fa-virus mr-2"></i>MEDEREDE
                    </div>
                </div>

                <!-- Menu Desktop -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="#funcionalidades" class="px-4 py-2 text-gray-700 hover:text-purple-600 transition-colors font-medium"></a>
                    <a href="#sobre" class="px-4 py-2 text-gray-700 hover:text-purple-600 transition-colors font-medium"></a>
                    <a href="#credenciais" class="px-4 py-2 text-gray-700 hover:text-purple-600 transition-colors font-medium"></a>
                </div>

                <!-- Botões Direita -->
                <div class="flex items-center space-x-3">
                    @if (auth()->check())
                        <span class="text-gray-600 text-sm">{{ auth()->user()->name }}</span>
                        <a href="/dashboard" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition-all">Dashboard</a>
                        <a href="/logout" class="px-4 py-2 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">Sair</a>
                    @else
                        <a href="/login" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:shadow-lg transition-all">Entrar</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="relative bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-32 px-4 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 rounded-full bg-white opacity-5"></div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    🦟 Monitorização de Doenças Endêmicas
                </h1>
                <p class="text-xl md:text-2xl mb-4 opacity-95">
                    MEDEREDE - Sistema Inteligente de Vigilância Epidemiológica
                </p>
                <p class="text-lg opacity-85 max-w-2xl mx-auto mb-8">
                    Rastreie, analise e responda a surtos em tempo real com dados precisos e inteligência epidemiológica avançada
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    @if (auth()->check())
                        <a href="/dashboard" class="px-8 py-3 bg-white text-purple-600 font-bold rounded-lg hover:shadow-xl transition-all">
                            <i class="fas fa-chart-line mr-2"></i>Ir para Dashboard
                        </a>
                    @else
                        <a href="/login" class="px-8 py-3 bg-white text-purple-600 font-bold rounded-lg hover:shadow-xl transition-all">
                            <i class="fas fa-sign-in-alt mr-2"></i>Fazer Login
                        </a>
                    @endif
                    <a href="#funcionalidades" class="px-8 py-3 border-2 border-white text-white font-bold rounded-lg hover:bg-white hover:text-purple-600 transition-all">
                        <i class="fas fa-arrow-down mr-2"></i>Saiba Mais
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 py-16">
        <!-- STATS SECTION -->
        <section class="mb-20">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-xl text-center card-hover">
                    <div class="text-4xl font-bold mb-2">5</div>
                    <div class="text-sm opacity-90">Doenças Monitoradas</div>
                </div>
                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-xl text-center card-hover">
                    <div class="text-4xl font-bold mb-2">20</div>
                    <div class="text-sm opacity-90">Casos Registados</div>
                </div>
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-xl text-center card-hover">
                    <div class="text-4xl font-bold mb-2">7</div>
                    <div class="text-sm opacity-90">Profissionais Ativos</div>
                </div>
                <div class="bg-gradient-to-br from-red-500 to-red-600 text-white p-6 rounded-xl text-center card-hover">
                    <div class="text-4xl font-bold mb-2">3</div>
                    <div class="text-sm opacity-90">Alertas Pendentes</div>
                </div>
            </div>
        </section>
   
    </div>

    <!-- FOOTER -->
    <footer class="bg-gray-900 text-gray-400 py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h4 class="text-white font-bold mb-4">MEDEREDE</h4>
                    <p>Sistema de Monitorização de Doenças Endêmicas</p>
                </div>
                
                <div>
                    <h4 class="text-white font-bold mb-4">Suporte</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-purple-400 transition">Documentação</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition">FAQ</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition">Contacte-nos</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-purple-400 transition">Privacidade</a></li>
                        <li><a href="#" class="hover:text-purple-400 transition">Termos</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-sm">
                <p>&copy; 2026 MEDEREDE. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
