<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'As credenciais fornecidas nÃ£o sÃ£o vÃ¡lidas.',
            ])->withInput();
        }

        // Registar o Ãºltimo login
        Auth::user()->update(['last_login_at' => now()]);

        $request->session()->regenerate();

        return redirect('/dashboard')->with('success', 'Login realizado com sucesso!');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|min:8|confirmed',
            'bilhete' => ['nullable', 'string', Rule::unique('users', 'bilhete'), Rule::unique('casos', 'bilhete')],
            'data_nascimento' => 'nullable|date',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email existente.',
            'bilhete.unique' => 'Bilhete existente.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'publico';
        $validated['status'] = 'ativo';
        
        User::create($validated);

        return redirect('/login')->with('success', 'Cadastro criado. O administrador pode ajustar o seu perfil depois.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout realizado com sucesso!');
    }
}
