<?php

namespace App\Modules\Seguridad\Repositories;

use App\Shared\Repositories\BaseEloquentRepository;
use App\Modules\Seguridad\Contracts\UsuarioRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentUsuarioRepository extends BaseEloquentRepository implements UsuarioRepositoryInterface
{
    protected function getModel(): Model
    {
        return new User();
    }

    public function listarTodos(int $porPagina = 15): LengthAwarePaginator
    {
        return User::latest()->paginate($porPagina);
    }

    public function buscarPorId(int $id): ?User
    {
        return User::find($id);
    }

    public function buscarPorEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function crear(array $datos): User
    {
        return User::create($datos);
    }

    public function actualizar(int $id, array $datos): User
    {
        $user = User::findOrFail($id);
        $user->update($datos);
        return $user;
    }

    public function eliminar(int $id): bool
    {
        $user = User::findOrFail($id);
        return (bool) $user->delete();
    }
}
