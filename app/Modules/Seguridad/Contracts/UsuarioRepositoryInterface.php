<?php

namespace App\Modules\Seguridad\Contracts;

use App\Shared\Contracts\BaseRepositoryInterface;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UsuarioRepositoryInterface extends BaseRepositoryInterface
{
    public function listarTodos(int $porPagina = 15): LengthAwarePaginator;
    public function buscarPorId(int $id): ?User;
    public function buscarPorEmail(string $email): ?User;
    public function crear(array $datos): User;
    public function actualizar(int $id, array $datos): User;
    public function eliminar(int $id): bool;
}
