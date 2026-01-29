<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - MEDEREDE</title>
    @vite('resources/css/app.css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-purple-600 to-indigo-600 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 p-8 text-center text-white">
                <div class="text-5xl mb-3"><i class="fas fa-user-plus"></i></div>
                <h1 class="text-3xl font-bold">MEDEREDE</h1>
                <p class="text-purple-100 mt-2">Cadastro de Utilizador</p>
            </div>

            <div class="p-8">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <p class="text-red-700 font-semibold mb-2">Erro no cadastro:</p>
                        @foreach ($errors->all() as $error)
                            <p class="text-red-600 text-sm">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="/register" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nome completo</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                               placeholder="Seu nome" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                               placeholder="seu@email.com" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Bilhete de Identidade</label>
                        <input type="text" name="bilhete" value="{{ old('bilhete') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                               placeholder="Ex: CC12345678">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Data de nascimento</label>
                        <input type="date" name="data_nascimento" value="{{ old('data_nascimento') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Senha</label>
                        <input type="password" name="password"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                               placeholder="********" required>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Confirmar senha</label>
                        <input type="password" name="password_confirmation"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition"
                               placeholder="********" required>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-2.5 rounded-lg hover:shadow-lg transition-all transform hover:scale-105 mt-4">
                        <i class="fas fa-user-plus mr-2"></i>Solicitar Cadastro
                    </button>
                </form>

                <div class="text-center mt-6">
                    <a href="/login" class="text-purple-600 hover:text-purple-700 font-semibold transition">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar ao Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
