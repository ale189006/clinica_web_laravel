<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta página');
        }

        $user = Auth::user();
        
        if (!$user->rol) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Tu usuario no tiene un rol asignado');
        }

        $userRole = strtolower($user->rol->rol);
        
        // Verificar si el usuario tiene alguno de los roles permitidos
        $allowedRoles = array_map('strtolower', $roles);
        
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'No tienes permiso para acceder a esta página');
        }

        return $next($request);
    }
}



