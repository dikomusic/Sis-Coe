<?php

namespace App\Modules\Seguridad\Services;

use App\Modules\Seguridad\Contracts\UsuarioRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function __construct(
        protected UsuarioRepositoryInterface $usuarioRepo
    ) {}

    public function registrarUsuario(array $datos): User
    {
        $datos['password'] = Hash::make($datos['password']);
        $datos['rol'] = strtoupper(trim($datos['rol']));
        return $this->usuarioRepo->crear($datos);
    }

    public function actualizarPassword(int $id, string $nuevaPassword): User
    {
        return $this->usuarioRepo->actualizar($id, [
            'password' => Hash::make($nuevaPassword),
        ]);
    }
}
