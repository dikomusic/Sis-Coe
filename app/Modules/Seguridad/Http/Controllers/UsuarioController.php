<?php

namespace App\Modules\Seguridad\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Seguridad\Contracts\UsuarioRepositoryInterface;
use App\Modules\Seguridad\Services\UsuarioService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UsuarioController extends Controller
{
    public function __construct(
        protected UsuarioRepositoryInterface $usuarioRepo,
        protected UsuarioService $usuarioService
    ) {}

    public function index(): View
    {
        $usuarios = $this->usuarioRepo->listarTodos(15);
        return view('pages.usuarios.index', compact('usuarios'));
    }

    public function create($rol = 'USUARIO'): View
    {
        return view('pages.usuarios.create', compact('rol'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'rol'      => 'required|in:ADMIN,CAJERO,CAJAS,USUARIO,INVITADO',
        ]);

        $this->usuarioService->registrarUsuario($request->all());

        return redirect()->route('admin.dashboard')->with('success', "Usuario creado exitosamente con rol {$request->rol}.");
    }
}
