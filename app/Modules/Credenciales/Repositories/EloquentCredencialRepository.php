<?php

namespace App\Modules\Credenciales\Repositories;

use App\Shared\Repositories\BaseEloquentRepository;
use App\Modules\Credenciales\Contracts\CredencialRepositoryInterface;
use App\Models\Credencial;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EloquentCredencialRepository extends BaseEloquentRepository implements CredencialRepositoryInterface
{
    protected function getModel(): Model
    {
        return new Credencial();
    }

    public function obtenerUltimaPorEntidad(string $entidadType, int $entidadId): ?Credencial
    {
        return Credencial::where('entidad_type', $entidadType)
            ->where('entidad_id', $entidadId)
            ->latest('fecha_emision')
            ->first();
    }

    public function listarPorEntidad(string $entidadType, int $entidadId): Collection
    {
        return Credencial::where('entidad_type', $entidadType)
            ->where('entidad_id', $entidadId)
            ->latest('fecha_emision')
            ->get();
    }

    public function buscarPorId(int $id): ?Credencial
    {
        return Credencial::find($id);
    }

    public function crear(array $datos): Credencial
    {
        return Credencial::create($datos);
    }

    public function actualizar(int $id, array $datos): Credencial
    {
        $cred = Credencial::findOrFail($id);
        $cred->update($datos);
        return $cred;
    }
}
