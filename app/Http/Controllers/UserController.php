<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        // Apenas admin pode gerenciar utilizadores
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Acesso negado. Apenas administradores podem gerenciar utilizadores.');
            }
            return $next($request);
        });
    }

    // Listar todos os utilizadores
    public function index()
    {
        $users = User::paginate(15);
        $roles = ['admin', 'profissional_saude', 'publico'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    // Formulario para criar novo utilizador
    public function create()
    {
        $roles = ['admin' => 'Administrador', 'profissional_saude' => 'Profissional de Saude', 'publico' => 'Publico'];
        return view('admin.users.create', compact('roles'));
    }

    // Guardar novo utilizador
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|min:8|confirmed',
            'role' => 'required|in:admin,profissional_saude,publico',
            'bilhete' => ['nullable', 'string', Rule::unique('users', 'bilhete'), Rule::unique('casos', 'bilhete')],
            'data_nascimento' => 'nullable|date',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email existente.',
            'bilhete.unique' => 'Bilhete existente.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'ativo';
        $validated['requested_role'] = null;

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Utilizador criado com sucesso!');
    }

    // Formulario para editar utilizador
    public function edit(User $user)
    {
        $roles = ['admin' => 'Administrador', 'profissional_saude' => 'Profissional de Saude', 'publico' => 'Publico'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Atualizar utilizador
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => 'required|in:admin,profissional_saude,publico',
            'bilhete' => ['nullable', 'string', Rule::unique('users', 'bilhete')->ignore($user->id), Rule::unique('casos', 'bilhete')],
            'data_nascimento' => 'nullable|date',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email existente.',
            'bilhete.unique' => 'Bilhete existente.',
        ]);

        $user->update($validated);

        return redirect()->route('users.show', $user)->with('success', 'Utilizador atualizado com sucesso!');
    }

    public function approve(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:profissional_saude',
        ]);

        if ($user->role === 'admin') {
            return redirect()->route('users.index')->with('error', 'Nao e possivel alterar o perfil de um administrador.');
        }

        $user->update([
            'role' => 'profissional_saude',
        ]);

        return redirect()->route('users.index')->with('success', 'Perfil do utilizador atualizado com sucesso!');
    }

    // Deletar utilizador
    public function destroy(User $user)
    {
        // Nao permitir deletar a si mesmo
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Nao pode deletar sua propria conta!');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilizador deletado com sucesso!');
    }

    // Resetar password de um utilizador
    public function resetPassword(User $user)
    {
        // Nao permitir resetar a si mesmo
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Use a pagina de perfil para mudar sua propria senha!');
        }

        // Gerar password temporaria
        $temporaryPassword = \Illuminate\Support\Str::random(12);

        $user->update([
            'password' => Hash::make($temporaryPassword),
        ]);

        return redirect()->route('users.index')->with('success', 'Password resetada! Nova senha temporaria: ' . $temporaryPassword);
    }

    // Ver detalhes do utilizador
    public function show(User $user)
    {
        $activities = $user->cases()->latest()->limit(5)->get(); // Se houver relacionamento
        return view('admin.users.show', compact('user', 'activities'));
    }
}
