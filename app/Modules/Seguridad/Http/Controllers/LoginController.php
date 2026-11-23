<?php

namespace App\Modules\Seguridad\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('pages.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isCajero()) {
                return redirect()->route('cajas.index');
            } elseif ($user->isUsuario()) {
                return redirect()->route('usuario.dashboard');
            } elseif ($user->isInvitado()) {
                return redirect()->route('consultas.index');
            }

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Las credenciales ingresadas son incorrectas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
