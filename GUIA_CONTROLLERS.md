# 📚 Guia de Implementação dos Controllers

## Exemplo 1: DoencaController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Doenca;
use Illuminate\Http\Request;

class DoencaController extends Controller
{
    /**
     * Display a listing of all diseases.
     */
    public function index()
    {
        return response()->json(Doenca::all());
    }

    /**
     * Store a newly created disease.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|unique:doencas',
            'codigo' => 'required|string|unique:doencas',
            'descricao' => 'nullable|string',
            'status' => 'required|in:ativa,inativa',
        ]);

        $doenca = Doenca::create($validated);
        return response()->json($doenca, 201);
    }

    /**
     * Display the specified disease.
     */
    public function show(Doenca $doenca)
    {
        return response()->json($doenca->load(['casos', 'notificacaos']));
    }

    /**
     * Update the specified disease.
     */
    public function update(Request $request, Doenca $doenca)
    {
        $validated = $request->validate([
            'nome' => 'string|unique:doencas,nome,' . $doenca->id,
            'codigo' => 'string|unique:doencas,codigo,' . $doenca->id,
            'descricao' => 'nullable|string',
            'status' => 'in:ativa,inativa',
        ]);

        $doenca->update(array_filter($validated));
        return response()->json($doenca);
    }

    /**
     * Remove the specified disease.
     */
    public function destroy(Doenca $doenca)
    {
        $doenca->delete();
        return response()->json(['message' => 'Doença eliminada com sucesso']);
    }
}
```

---

## Exemplo 2: CasoController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use Illuminate\Http\Request;

class CasoController extends Controller
{
    /**
     * Display a listing of cases.
     */
    public function index(Request $request)
    {
        $query = Caso::with(['doenca', 'user']);

        // Filtrar por doença
        if ($request->has('doenca_id')) {
            $query->where('doenca_id', $request->doenca_id);
        }

        // Filtrar por status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filtrar por localização
        if ($request->has('localizacao')) {
            $query->where('localizacao', 'like', '%' . $request->localizacao . '%');
        }

        return response()->json($query->paginate(15));
    }

    /**
     * Store a newly created case.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_nome' => 'required|string',
            'idade' => 'nullable|integer|min:0|max:150',
            'localizacao' => 'required|string',
            'data_inicio' => 'required|date',
            'sintomas' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'required|in:confirmado,suspeito,descartado',
            'doenca_id' => 'required|exists:doencas,id',
        ]);

        $validated['user_id'] = auth()->id();
        $caso = Caso::create($validated);

        return response()->json($caso->load(['doenca', 'user']), 201);
    }

    /**
     * Display the specified case.
     */
    public function show(Caso $caso)
    {
        return response()->json($caso->load(['doenca', 'user', 'alertas']));
    }

    /**
     * Update the specified case.
     */
    public function update(Request $request, Caso $caso)
    {
        $validated = $request->validate([
            'paciente_nome' => 'string',
            'idade' => 'nullable|integer|min:0|max:150',
            'localizacao' => 'string',
            'data_inicio' => 'date',
            'sintomas' => 'string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'status' => 'in:confirmado,suspeito,descartado',
            'doenca_id' => 'exists:doencas,id',
        ]);

        $caso->update(array_filter($validated));
        return response()->json($caso);
    }

    /**
     * Remove the specified case.
     */
    public function destroy(Caso $caso)
    {
        $caso->delete();
        return response()->json(['message' => 'Caso eliminado com sucesso']);
    }
}
```

---

## Exemplo 3: AlertaController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Alerta;
use Illuminate\Http\Request;

class AlertaController extends Controller
{
    /**
     * Display a listing of alerts.
     */
    public function index(Request $request)
    {
        $query = Alerta::with(['caso', 'user']);

        if ($request->has('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderBy('data_alerta', 'desc')->paginate(20));
    }

    /**
     * Store a newly created alert.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'mensagem' => 'required|string',
            'tipo' => 'required|in:email,sms,notificacao',
            'data_alerta' => 'required|date_format:Y-m-d H:i:s',
            'caso_id' => 'required|exists:casos,id',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pendente';

        $alerta = Alerta::create($validated);

        // TODO: Implementar envio de alerta via email/SMS

        return response()->json($alerta, 201);
    }

    /**
     * Display the specified alert.
     */
    public function show(Alerta $alerta)
    {
        return response()->json($alerta->load(['caso', 'user']));
    }

    /**
     * Update the specified alert.
     */
    public function update(Request $request, Alerta $alerta)
    {
        $validated = $request->validate([
            'titulo' => 'string',
            'mensagem' => 'string',
            'tipo' => 'in:email,sms,notificacao',
            'status' => 'in:enviado,pendente,falhou',
            'data_alerta' => 'date_format:Y-m-d H:i:s',
        ]);

        $alerta->update(array_filter($validated));
        return response()->json($alerta);
    }

    /**
     * Remove the specified alert.
     */
    public function destroy(Alerta $alerta)
    {
        $alerta->delete();
        return response()->json(['message' => 'Alerta eliminado com sucesso']);
    }
}
```

---

## Exemplo 4: RelatorioController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Relatorio;
use App\Models\Caso;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index(Request $request)
    {
        $query = Relatorio::with('user');

        // Se não for admin, mostrar apenas seus próprios relatórios
        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }

        return response()->json($query->orderBy('data_geracao', 'desc')->paginate(15));
    }

    /**
     * Generate a new report.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'tipo' => 'required|in:PDF,CSV',
            'formato_analise' => 'required|in:temporal,geografico,estatistico',
            'filtros' => 'nullable|json', // Exemplo: {"doenca_id": 1, "status": "confirmado"}
        ]);

        $relatorio = new Relatorio();
        $relatorio->fill($validated);
        $relatorio->user_id = auth()->id();
        $relatorio->data_geracao = now();

        // TODO: Implementar geração de relatório
        // Gerar arquivo e guardar caminho
        // $relatorio->caminho_arquivo = $this->generateReport($validated, $relatorio->tipo);

        $relatorio->save();

        return response()->json($relatorio, 201);
    }

    /**
     * Display the specified report.
     */
    public function show(Relatorio $relatorio)
    {
        $this->authorize('view', $relatorio);
        return response()->json($relatorio);
    }

    /**
     * Remove the specified report.
     */
    public function destroy(Relatorio $relatorio)
    {
        $this->authorize('delete', $relatorio);

        if ($relatorio->caminho_arquivo && file_exists(storage_path($relatorio->caminho_arquivo))) {
            unlink(storage_path($relatorio->caminho_arquivo));
        }

        $relatorio->delete();
        return response()->json(['message' => 'Relatório eliminado com sucesso']);
    }

    /**
     * Generate statistical report (exemplo de método auxiliar).
     */
    private function generateReport($filtros, $tipo)
    {
        $casos = Caso::query();

        if (isset($filtros['doenca_id'])) {
            $casos->where('doenca_id', $filtros['doenca_id']);
        }

        if (isset($filtros['status'])) {
            $casos->where('status', $filtros['status']);
        }

        $dados = $casos->get();

        // Gerar arquivo (PDF/CSV) com os dados
        // ...

        return 'relatorios/relatorio_' . time() . '.' . ($tipo === 'PDF' ? 'pdf' : 'csv');
    }
}
```

---

## Exemplo 5: NotificacaoController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index()
    {
        return response()->json(
            Notificacao::where('status', 'ativa')
                ->orderBy('data_publicacao', 'desc')
                ->with('doenca')
                ->paginate(10)
        );
    }

    /**
     * Store a newly created notification (Admin only).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string',
            'conteudo' => 'required|string',
            'tipo' => 'required|in:prevencao,informacao,alerta',
            'data_publicacao' => 'required|date',
            'status' => 'required|in:ativa,inativa,arquivada',
            'doenca_id' => 'required|exists:doencas,id',
        ]);

        $notificacao = Notificacao::create($validated);
        return response()->json($notificacao, 201);
    }

    /**
     * Display the specified notification.
     */
    public function show(Notificacao $notificacao)
    {
        return response()->json($notificacao->load('doenca'));
    }

    /**
     * Update the specified notification (Admin only).
     */
    public function update(Request $request, Notificacao $notificacao)
    {
        $validated = $request->validate([
            'titulo' => 'string',
            'conteudo' => 'string',
            'tipo' => 'in:prevencao,informacao,alerta',
            'data_publicacao' => 'date',
            'status' => 'in:ativa,inativa,arquivada',
            'doenca_id' => 'exists:doencas,id',
        ]);

        $notificacao->update(array_filter($validated));
        return response()->json($notificacao);
    }

    /**
     * Remove the specified notification (Admin only).
     */
    public function destroy(Notificacao $notificacao)
    {
        $notificacao->delete();
        return response()->json(['message' => 'Notificação eliminada com sucesso']);
    }
}
```

---

## 🔐 Policy Exemplo (Opcional)

Para controlar acesso ao nível de recurso:

```php
<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Relatorio;

class RelatorioPolicy
{
    public function view(User $user, Relatorio $relatorio)
    {
        return $user->id === $relatorio->user_id || $user->role === 'admin';
    }

    public function delete(User $user, Relatorio $relatorio)
    {
        return $user->id === $relatorio->user_id || $user->role === 'admin';
    }
}
```

---

## 📝 Notas

- Todos os Controllers usam validação básica
- Adicionar `protected $guarded = [];` aos Models se necessário
- Implementar Policies para autorização mais granular
- Adicionar tratamento de erros e exceções
- Implementar queues para tarefas longas (geração de relatórios)
- Adicionar logging de ações importantes

