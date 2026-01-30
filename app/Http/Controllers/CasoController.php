<?php

namespace App\Http\Controllers;

use App\Models\Caso;
use App\Models\Doenca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\SvgWriter;

class CasoController extends Controller
{
    public function index()
    {
        $query = Caso::with('doenca', 'user');

        // Filtro por Status
        if (request('status')) {
            $query->where('status', request('status'));
        }

        // Filtro por Doença
        if (request('doenca_id')) {
            $query->where('doenca_id', request('doenca_id'));
        }

        // Filtro por Data De
        if (request('data_de')) {
            $query->whereDate('data_inicio', '>=', request('data_de'));
        }

        // Filtro por Data Até
        if (request('data_ate')) {
            $query->whereDate('data_inicio', '<=', request('data_ate'));
        }

        // Filtro por Província / Município
        if (request('provincia')) {
            $query->where('localizacao', 'like', '%' . request('provincia') . '%');
        }
        if (request('municipio')) {
            $query->where('localizacao', 'like', '%' . request('municipio') . '%');
        }
        if (request('localizacao')) {
            $query->where('localizacao', 'like', '%' . request('localizacao') . '%');
        }

        // Filtro por Nome do Paciente
        if (request('paciente')) {
            $query->where('paciente_nome', 'like', '%' . request('paciente') . '%');
        }

        $casos = $query->paginate(15);
        $doencas = Doenca::all();

        return view('casos.index', compact('casos', 'doencas'));
    }

    public function create()
    {
        $doencas = Doenca::where('status', 'ativa')->get();
        return view('casos.create', compact('doencas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_nome' => 'required|string|max:255',
            'doenca_id' => 'required|exists:doencas,id',
            'status' => 'required|in:confirmado,suspeito,descartado',
            'data_inicio' => 'required|date',
            'provincia' => 'required_without:localizacao|string|max:255',
            'municipio' => 'required_without:localizacao|string|max:255',
            'localizacao' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'sintomas' => 'nullable|string',
            'bilhete' => ['nullable', 'string', Rule::unique('casos', 'bilhete'), Rule::unique('users', 'bilhete')],
            'data_nascimento' => 'nullable|date',
        ], [
            'bilhete.unique' => 'Bilhete existente.',
        ]);

        $localizacao = trim($validated['localizacao'] ?? '');
        if ($localizacao === '') {
            $provincia = trim($validated['provincia'] ?? '');
            $municipio = trim($validated['municipio'] ?? '');
            $localizacao = $provincia;
            if ($municipio !== '') {
                $localizacao = $localizacao === '' ? $municipio : ($localizacao . ', ' . $municipio);
            }
        }
        $validated['localizacao'] = $localizacao;
        unset($validated['provincia'], $validated['municipio']);

        if (is_null($validated['latitude']) || is_null($validated['longitude'])) {
            $geo = $this->geocodeLocation($validated['localizacao']);
            if ($geo) {
                $validated['latitude'] = $geo['lat'];
                $validated['longitude'] = $geo['lng'];
            } else {
                return back()->withErrors([
                    'provincia' => 'Localizacao nao encontrada. Informe provincia e municipio mais especificos.',
                ])->withInput();
            }
        }

        $validated['user_id'] = Auth::id();
        Caso::create($validated);

        return redirect('/casos')->with('success', 'Caso registado com sucesso!');
    }

    public function show(string $id)
    {
        $caso = Caso::with('doenca', 'user')->findOrFail($id);
        
        // Gerar código QR com bilhete + data_nascimento + nome
        $qrData = json_encode([
            'nome' => $caso->paciente_nome,
            'bilhete' => $caso->bilhete ?? 'N/A',
            'data_nascimento' => $caso->data_nascimento ? $caso->data_nascimento->format('d/m/Y') : 'N/A',
        ]);
        
        $qrCode = new QrCode($qrData);
        $writer = new SvgWriter();
        $qrCodeSvg = $writer->write($qrCode)->getString();
        
        return view('casos.show', compact('caso', 'qrCodeSvg'));
    }

    public function print(string $id)
    {
        $caso = Caso::with('doenca', 'user')->findOrFail($id);

        return view('casos.print', compact('caso'));
    }

    public function edit(string $id)
    {
        $caso = Caso::findOrFail($id);
        $doencas = Doenca::all();
        return view('casos.edit', compact('caso', 'doencas'));
    }

    public function update(Request $request, string $id)
    {
        $caso = Caso::findOrFail($id);

        $validated = $request->validate([
            'paciente_nome' => 'required|string|max:255',
            'doenca_id' => 'required|exists:doencas,id',
            'status' => 'required|in:confirmado,suspeito,descartado',
            'data_inicio' => 'required|date',
            'provincia' => 'required_without:localizacao|string|max:255',
            'municipio' => 'required_without:localizacao|string|max:255',
            'localizacao' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'sintomas' => 'nullable|string',
            'bilhete' => ['nullable', 'string', Rule::unique('casos', 'bilhete')->ignore($caso->id), Rule::unique('users', 'bilhete')],
            'data_nascimento' => 'nullable|date',
        ], [
            'bilhete.unique' => 'Bilhete existente.',
        ]);

        $localizacao = trim($validated['localizacao'] ?? '');
        if ($localizacao === '') {
            $provincia = trim($validated['provincia'] ?? '');
            $municipio = trim($validated['municipio'] ?? '');
            $localizacao = $provincia;
            if ($municipio !== '') {
                $localizacao = $localizacao === '' ? $municipio : ($localizacao . ', ' . $municipio);
            }
        }
        $validated['localizacao'] = $localizacao;
        unset($validated['provincia'], $validated['municipio']);

        if (is_null($validated['latitude']) || is_null($validated['longitude'])) {
            $geo = $this->geocodeLocation($validated['localizacao']);
            if ($geo) {
                $validated['latitude'] = $geo['lat'];
                $validated['longitude'] = $geo['lng'];
            } else {
                return back()->withErrors([
                    'provincia' => 'Localizacao nao encontrada. Informe provincia e municipio mais especificos.',
                ])->withInput();
            }
        }

        $caso->update($validated);

        return redirect('/casos')->with('success', 'Caso actualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $caso = Caso::findOrFail($id);
        $caso->delete();

        return redirect('/casos')->with('success', 'Caso eliminado com sucesso!');
    }

    public function getCasosByPaciente(string $nome)
    {
        $casos = Caso::with('doenca', 'user')
            ->where('paciente_nome', $nome)
            ->get();
        
        return response()->json([
            'casos' => $casos,
            'total' => $casos->count()
        ]);
    }

    private function geocodeLocation(string $query): ?array
    {
        $response = Http::withHeaders([
            'User-Agent' => 'mederede/1.0',
        ])->get('https://nominatim.openstreetmap.org/search', [
            'q' => $query,
            'format' => 'json',
            'limit' => 1,
        ]);

        if (!$response->ok()) {
            return null;
        }

        $data = $response->json();
        if (!is_array($data) || empty($data)) {
            return null;
        }

        return [
            'lat' => (float) $data[0]['lat'],
            'lng' => (float) $data[0]['lon'],
        ];
    }
}
