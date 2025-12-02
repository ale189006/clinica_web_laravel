<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ], [
            'usuario.required' => 'El nombre de usuario es obligatorio',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        $credentials = [
            'usuario' => $request->usuario,
            'password' => $request->password,
        ];

        // Intentar autenticar por nombre de usuario
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'))
                           ->with('success', 'Bienvenido, ' . Auth::user()->usuario);
        }

        // Si falla, intentar por correo
        $usuario = Usuario::where('correo', $request->usuario)->first();
        
        if ($usuario && Auth::attempt([
            'usuario' => $usuario->usuario,
            'password' => $request->password,
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard'))
                           ->with('success', 'Bienvenido, ' . Auth::user()->usuario);
        }

        return back()->withErrors([
            'usuario' => 'Las credenciales proporcionadas no son correctas.',
        ])->withInput($request->only('usuario'));
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
                       ->with('success', 'Has cerrado sesión correctamente');
    }
}



