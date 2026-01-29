<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CasoController;
use App\Http\Controllers\QRCodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rotas Públicas
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', function () {
    return view('login');
})->name('login')->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])->middleware('guest');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Rotas Protegidas com Autenticação
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Casos
    Route::get('/casos', [CasoController::class, 'index'])->name('casos.index');
    Route::get('/casos/create', [CasoController::class, 'create'])->middleware('health_professional')->name('casos.create');
    Route::post('/casos', [CasoController::class, 'store'])->middleware('health_professional')->name('casos.store');
    Route::get('/casos/{caso}', [CasoController::class, 'show'])->whereNumber('caso')->name('casos.show');
    Route::get('/casos/{caso}/edit', [CasoController::class, 'edit'])->whereNumber('caso')->middleware('health_professional')->name('casos.edit');
    Route::put('/casos/{caso}', [CasoController::class, 'update'])->whereNumber('caso')->middleware('health_professional')->name('casos.update');
    Route::delete('/casos/{caso}', [CasoController::class, 'destroy'])->whereNumber('caso')->middleware('admin')->name('casos.destroy');
    Route::get('/casos/{caso}/imprimir', [CasoController::class, 'print'])->middleware('health_professional')->name('casos.print');

    // Alertas
    Route::get('/alertas', function () {
        $alertas = \App\Models\Alerta::with('caso', 'user')
            ->orderBy('data_alerta', 'desc')
            ->paginate(10);
        $totalAlertas = \App\Models\Alerta::count();
        $pendentes = \App\Models\Alerta::where('status', 'pendente')->count();
        $enviados = \App\Models\Alerta::where('status', 'enviado')->count();
        $falhou = \App\Models\Alerta::where('status', 'falhou')->count();
        $tipoCounts = \App\Models\Alerta::selectRaw('tipo, count(*) as total')
            ->groupBy('tipo')
            ->pluck('total', 'tipo');

        return view('alertas.index', compact('alertas', 'totalAlertas', 'pendentes', 'enviados', 'falhou', 'tipoCounts'));
    })->name('alertas.index');

    Route::get('/alertas/create', function () {
        $casos = \App\Models\Caso::all();
        return view('alertas.create', compact('casos'));
    })->middleware('health_professional')->name('alertas.create');

    Route::post('/alertas', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'caso_id' => 'required|exists:casos,id',
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:email,sms,notificacao',
            'mensagem' => 'required|string',
            'data_alerta' => 'required|date',
        ]);

        \App\Models\Alerta::create([
            'caso_id' => $request->caso_id,
            'titulo' => $request->titulo,
            'tipo' => $request->tipo,
            'mensagem' => $request->mensagem,
            'data_alerta' => $request->data_alerta,
            'status' => 'enviado',
            'user_id' => auth()->id(),
        ]);

        return redirect('/alertas')->with('success', 'Alerta criado com sucesso!');
    })->middleware('health_professional')->name('alertas.store');

    Route::get('/alertas/{alerta}/edit', function (\App\Models\Alerta $alerta) {
        $casos = \App\Models\Caso::all();
        return view('alertas.edit', compact('alerta', 'casos'));
    })->middleware('health_professional')->name('alertas.edit');

    Route::put('/alertas/{alerta}', function (\Illuminate\Http\Request $request, \App\Models\Alerta $alerta) {
        $request->validate([
            'caso_id' => 'required|exists:casos,id',
            'titulo' => 'required|string|max:255',
            'tipo' => 'required|in:email,sms,notificacao',
            'mensagem' => 'required|string',
            'data_alerta' => 'required|date',
            'status' => 'required|in:pendente,enviado,falhou',
        ]);

        $alerta->update([
            'caso_id' => $request->caso_id,
            'titulo' => $request->titulo,
            'tipo' => $request->tipo,
            'mensagem' => $request->mensagem,
            'data_alerta' => $request->data_alerta,
            'status' => $request->status,
        ]);

        return redirect('/alertas')->with('success', 'Alerta atualizado com sucesso!');
    })->middleware('health_professional')->name('alertas.update');

    Route::delete('/alertas/{alerta}', function (\App\Models\Alerta $alerta) {
        $alerta->delete();
        return redirect('/alertas')->with('success', 'Alerta eliminado com sucesso!');
    })->middleware('admin')->name('alertas.destroy');

    // Relatórios
    Route::get('/relatorios', function () {
        $relatorios = \App\Models\Relatorio::where('user_id', auth()->id())->paginate(10);
        return view('relatorios.index', compact('relatorios'));
    })->middleware('health_professional')->name('relatorios.index');

    Route::post('/relatorios', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'tipo' => 'required|in:PDF,CSV',
            'periodo' => 'required|in:custom',
            'data_inicio' => 'required|date',
            'data_fim' => 'required|date|after_or_equal:data_inicio',
            'sections' => 'required|in:all,casos,alertas,evolucao,distribuicao_doencas,casos_status,alertas_recentes,casos_recentes,dados_geograficos,resumo_doencas',
        ], [
            'sections.required' => 'Selecione o tipo de relatorio antes de gerar.',
            'sections.in' => 'Escolha um tipo de relatorio valido.',
            'data_inicio.required' => 'Informe a data inicial.',
            'data_fim.required' => 'Informe a data final.',
            'data_fim.after_or_equal' => 'A data final deve ser igual ou maior que a data inicial.',
        ]);

        $periodo = $request->periodo;
        $now = now();
        if ($periodo === 'custom') {
            $inicio = $request->data_inicio ? \Illuminate\Support\Carbon::parse($request->data_inicio)->startOfDay() : $now->copy()->startOfDay();
            $fim = $request->data_fim ? \Illuminate\Support\Carbon::parse($request->data_fim)->endOfDay() : $now->copy()->endOfDay();
            $periodoLabel = 'Personalizado';
        }
        if ($periodo === 'diario' && !isset($inicio)) {
            $inicio = $now->copy()->startOfDay();
            $fim = $now->copy()->endOfDay();
            $periodoLabel = 'Diario';
        } elseif ($periodo === 'semanal') {
            $inicio = $now->copy()->subDays(6)->startOfDay();
            $fim = $now->copy()->endOfDay();
            $periodoLabel = 'Semanal';
        } elseif ($periodo === 'mensal') {
            $inicio = $now->copy()->startOfMonth();
            $fim = $now->copy()->endOfMonth();
            $periodoLabel = 'Mensal';
        } else {
            $inicio = $now->copy()->subMonths(5)->startOfMonth();
            $fim = $now->copy()->endOfDay();
            $periodoLabel = 'Semestral';
        }

        $titulo = 'Relatorio ' . $periodoLabel . ' (' . $inicio->format('d/m/Y') . ' - ' . $fim->format('d/m/Y') . ')';

        $allSections = [
            'casos', 'alertas', 'evolucao', 'distribuicao_doencas', 'casos_status',
            'alertas_recentes', 'casos_recentes', 'dados_geograficos', 'resumo_doencas'
        ];

        $sectionsInput = $request->input('sections', []);
        if (is_string($sectionsInput)) {
            $sectionsInput = [$sectionsInput];
        }
        $sections = $sectionsInput;
        if (empty($sections) || in_array('all', $sections, true)) {
            $sections = $allSections;
        }

        $casosQuery = \App\Models\Caso::with('doenca', 'user')
            ->whereBetween('data_inicio', [$inicio, $fim]);

        $alertasQuery = \App\Models\Alerta::with('caso', 'user')
            ->whereBetween('data_alerta', [$inicio, $fim]);

        $data = [
            'titulo' => $titulo,
            'periodoLabel' => $periodoLabel,
            'inicio' => $inicio,
            'fim' => $fim,
            'sections' => $sections,
        ];

        if (in_array('casos', $sections)) {
            $data['casos'] = $casosQuery->get();
        }
        if (in_array('alertas', $sections)) {
            $data['alertas'] = $alertasQuery->get();
        }
        if (in_array('evolucao', $sections)) {
            $labels = [];
            $values = [];
            $cursor = $inicio->copy();
            while ($cursor->lte($fim)) {
                $labels[] = $cursor->format('d/m');
                $values[] = \App\Models\Caso::whereDate('data_inicio', $cursor)->count();
                $cursor->addDay();
            }
            $data['evolucaoLabels'] = $labels;
            $data['evolucaoData'] = $values;
        }
        if (in_array('distribuicao_doencas', $sections)) {
            $data['distribuicaoDoencas'] = \App\Models\Caso::selectRaw('doencas.nome, count(casos.id) as total')
                ->join('doencas', 'casos.doenca_id', '=', 'doencas.id')
                ->whereBetween('data_inicio', [$inicio, $fim])
                ->groupBy('doencas.nome')
                ->orderBy('total', 'DESC')
                ->get();
        }
        if (in_array('casos_status', $sections)) {
            $data['casosStatus'] = \App\Models\Caso::whereBetween('data_inicio', [$inicio, $fim])
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status');
        }
        if (in_array('alertas_recentes', $sections)) {
            $data['alertasRecentes'] = $alertasQuery->orderBy('data_alerta', 'desc')->limit(5)->get();
        }
        if (in_array('casos_recentes', $sections)) {
            $data['casosRecentes'] = $casosQuery->orderBy('created_at', 'desc')->limit(5)->get();
        }
        if (in_array('dados_geograficos', $sections)) {
            $data['dadosGeograficos'] = $casosQuery->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get(['paciente_nome', 'latitude', 'longitude', 'localizacao']);
        }
        if (in_array('resumo_doencas', $sections)) {
            $data['resumoDoencas'] = \App\Models\Caso::selectRaw('doencas.nome, count(casos.id) as total')
                ->join('doencas', 'casos.doenca_id', '=', 'doencas.id')
                ->whereBetween('data_inicio', [$inicio, $fim])
                ->groupBy('doencas.nome')
                ->orderBy('total', 'DESC')
                ->get();
        }

        \App\Models\Relatorio::create([
            'titulo' => $titulo,
            'tipo' => $request->tipo,
            'formato_analise' => 'estatistico',
            'data_geracao' => now(),
            'filtros' => json_encode(['periodo' => $periodo, 'sections' => $sections]),
            'user_id' => auth()->id(),
        ]);

        if ($request->boolean('preview')) {
            return view('relatorios.report', $data);
        }

        if ($request->tipo === 'CSV') {
            $temp = fopen('php://temp', 'r+');
            fputcsv($temp, ['Relatorio', $titulo]);
            fputcsv($temp, ['Periodo', $periodoLabel, $inicio->toDateString(), $fim->toDateString()]);
            fputcsv($temp, []);

            if (in_array('casos', $sections)) {
                fputcsv($temp, ['Casos Cadastrados']);
                fputcsv($temp, ['ID', 'Paciente', 'Doenca', 'Status', 'Localizacao', 'Data Inicio']);
                foreach ($data['casos'] as $caso) {
                    fputcsv($temp, [$caso->id, $caso->paciente_nome, $caso->doenca?->nome, $caso->status, $caso->localizacao, optional($caso->data_inicio)->format('Y-m-d')]);
                }
                fputcsv($temp, []);
            }
            if (in_array('alertas', $sections)) {
                fputcsv($temp, ['Alertas']);
                fputcsv($temp, ['ID', 'Caso', 'Titulo', 'Tipo', 'Status', 'Data']);
                foreach ($data['alertas'] as $alerta) {
                    fputcsv($temp, [$alerta->id, $alerta->caso_id, $alerta->titulo, $alerta->tipo, $alerta->status, optional($alerta->data_alerta)->format('Y-m-d H:i')]);
                }
                fputcsv($temp, []);
            }
            if (in_array('evolucao', $sections)) {
                fputcsv($temp, ['Evolucao Temporal']);
                fputcsv($temp, ['Data', 'Total']);
                foreach ($data['evolucaoLabels'] as $idx => $label) {
                    fputcsv($temp, [$label, $data['evolucaoData'][$idx]]);
                }
                fputcsv($temp, []);
            }
            if (in_array('distribuicao_doencas', $sections)) {
                fputcsv($temp, ['Distribuicao de Doencas']);
                fputcsv($temp, ['Doenca', 'Total']);
                foreach ($data['distribuicaoDoencas'] as $item) {
                    fputcsv($temp, [$item->nome, $item->total]);
                }
                fputcsv($temp, []);
            }
            if (in_array('casos_status', $sections)) {
                fputcsv($temp, ['Casos por Status']);
                fputcsv($temp, ['Status', 'Total']);
                foreach ($data['casosStatus'] as $status => $total) {
                    fputcsv($temp, [$status, $total]);
                }
                fputcsv($temp, []);
            }
            if (in_array('alertas_recentes', $sections)) {
                fputcsv($temp, ['Alertas Recentes']);
                fputcsv($temp, ['ID', 'Titulo', 'Tipo', 'Status', 'Data']);
                foreach ($data['alertasRecentes'] as $alerta) {
                    fputcsv($temp, [$alerta->id, $alerta->titulo, $alerta->tipo, $alerta->status, optional($alerta->data_alerta)->format('Y-m-d H:i')]);
                }
                fputcsv($temp, []);
            }
            if (in_array('casos_recentes', $sections)) {
                fputcsv($temp, ['Casos Recentes']);
                fputcsv($temp, ['ID', 'Paciente', 'Doenca', 'Status', 'Data']);
                foreach ($data['casosRecentes'] as $caso) {
                    fputcsv($temp, [$caso->id, $caso->paciente_nome, $caso->doenca?->nome, $caso->status, optional($caso->data_inicio)->format('Y-m-d')]);
                }
                fputcsv($temp, []);
            }
            if (in_array('dados_geograficos', $sections)) {
                fputcsv($temp, ['Dados Geograficos']);
                fputcsv($temp, ['Paciente', 'Latitude', 'Longitude', 'Localizacao']);
                foreach ($data['dadosGeograficos'] as $item) {
                    fputcsv($temp, [$item->paciente_nome, $item->latitude, $item->longitude, $item->localizacao]);
                }
                fputcsv($temp, []);
            }
            if (in_array('resumo_doencas', $sections)) {
                fputcsv($temp, ['Resumo por Doencas']);
                fputcsv($temp, ['Doenca', 'Total']);
                foreach ($data['resumoDoencas'] as $item) {
                    fputcsv($temp, [$item->nome, $item->total]);
                }
                fputcsv($temp, []);
            }

            rewind($temp);
            $csv = stream_get_contents($temp);
            fclose($temp);
            $filename = 'relatorio_' . now()->format('Ymd_His') . '.csv';

            return response($csv)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('relatorios.report', $data);
        $filename = 'relatorio_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    })->middleware('health_professional')->name('relatorios.store');

    // Perfil
    Route::get('/perfil', function () {
        return view('perfil');
    })->name('perfil');

    // QR Code - Validação de Identidade
    Route::get('/qrcode', [QRCodeController::class, 'listUsers'])->middleware('health_professional')->name('qrcode.list');
    Route::get('/qrcode/{user}/gerar', [QRCodeController::class, 'generate'])->middleware('health_professional')->name('qrcode.generate');
    Route::get('/qrcode/{user}/download', [QRCodeController::class, 'download'])->middleware('health_professional')->name('qrcode.download');
    Route::get('/qrcode/validar', [QRCodeController::class, 'showValidate'])->middleware('health_professional')->name('qrcode.validate');
    Route::post('/qrcode/verificar', [QRCodeController::class, 'checkValidity'])->middleware('health_professional')->name('qrcode.check');

    // Rotas Admin - Gerenciamento de Utilizadores
    Route::middleware('admin')->prefix('admin/utilizadores')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/criar', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}', [UserController::class, 'show'])->name('show');
        Route::get('/{user}/editar', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/{user}/aprovar', [UserController::class, 'approve'])->name('approve');
        Route::post('/{user}/resetar-password', [UserController::class, 'resetPassword'])->name('reset-password');
    });
});
