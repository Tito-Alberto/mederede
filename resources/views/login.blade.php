<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MEDEREDE</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-purple-600 to-indigo-600 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-8 text-center text-white">
                <div class="text-5xl mb-3">🦟</div>
                <h1 class="text-3xl font-bold">MEDEREDE</h1>
                <p class="text-purple-100 mt-2">Sistema de Vigilância Epidemiológica</p>
            </div>

            <!-- Form -->
            <div class="p-8">
                @if (session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                        <p class="text-green-700 font-semibold">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <p class="text-red-700 font-semibold mb-2">Erro no login:</p>
                        @foreach ($errors->all() as $error)
                            <p class="text-red-600 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="/login" class="space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-4 top-3.5 text-purple-500"></i>
                            <input 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                                placeholder="seu@email.com"
                                required
                            >
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Palavra-passe</label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-4 top-3.5 text-purple-500"></i>
                            <input 
                                type="password" 
                                name="password" 
                                class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                                placeholder="••••••••"
                                required
                            >
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-2.5 rounded-lg hover:shadow-lg transition-all transform hover:scale-105 mt-6"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>Entrar no Sistema
                    </button>
                </form>

                <!-- Remember Me -->
                <div class="flex items-center mt-4">
                    <input type="checkbox" id="remember" class="w-4 h-4 text-purple-600" checked>
                    <label for="remember" class="ml-2 text-gray-600 text-sm">Manter-me conectado</label>
                </div>

                <!-- Back Link -->
                <div class="text-center mt-6">
                    <a href="/" class="text-purple-600 hover:text-purple-700 font-semibold transition">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar ao Início
                    </a>
                </div>

                <div class="text-center mt-4">
                    <a href="/register" class="text-purple-600 hover:text-purple-700 font-semibold transition">
                        <i class="fas fa-user-plus mr-2"></i>Criar conta
                    </a>
                </div>
            </div>

            
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-white">
            <p class="text-sm opacity-80">MEDEREDE v1.0 • Sistema de Vigilância Epidemiológica</p>
        </div>
    </div>
</body>
</html>
