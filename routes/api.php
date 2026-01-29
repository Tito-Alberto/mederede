<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DoencaController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\NotificacaoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas públicas (sem autenticação)
Route::get('/notificacaos', [NotificacaoController::class, 'index']);
Route::get('/notificacaos/{notificacao}', [NotificacaoController::class, 'show']);

// Rotas protegidas - Profissionais de Saúde e Administradores
Route::middleware(['auth:sanctum', 'role:profissional_saude,admin'])->group(function () {
    // Rota para obter todos os casos de um paciente
    Route::get('/casos-paciente/{nome}', [CasoController::class, 'getCasosByPaciente']);
    
    // Casos - CRUD completo
    Route::apiResource('casos', CasoController::class);
    
    // Alertas
    Route::apiResource('alertas', AlertaController::class);
    
    // Relatórios - Visualizar e criar
    Route::get('relatorios', [RelatorioController::class, 'index']);
    Route::post('relatorios', [RelatorioController::class, 'store']);
    Route::get('relatorios/{relatorio}', [RelatorioController::class, 'show']);
    Route::delete('relatorios/{relatorio}', [RelatorioController::class, 'destroy']);
});

// Rotas protegidas - Apenas Administradores
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Doenças - CRUD completo
    Route::apiResource('doencas', DoencaController::class);
    
    // Notificações - CRUD completo
    Route::apiResource('notificacaos', NotificacaoController::class);
});

