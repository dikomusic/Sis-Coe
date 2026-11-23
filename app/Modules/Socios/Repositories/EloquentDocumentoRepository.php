<?php

namespace App\Modules\Socios\Repositories;

use App\Modules\Socios\Contracts\DocumentoRepositoryInterface;
use App\Models\DocumentoRespaldo;
use Illuminate\Support\Collection;

class EloquentDocumentoRepository implements DocumentoRepositoryInterface
{
    public function listarPorEntidad(string $tipo, int $id): Collection
    {
        $campo = $tipo === 'titular' ? 'id_titular' : 'id_beneficiario';
        return DocumentoRespaldo::where($campo, $id)->latest()->get();
    }

    public function buscarPorId(int $id): ?DocumentoRespaldo
    {
        return DocumentoRespaldo::find($id);
    }

    public function crear(array $datos): DocumentoRespaldo
    {
        return DocumentoRespaldo::create($datos);
    }

    public function actualizar(int $id, array $datos): DocumentoRespaldo
    {
        $doc = DocumentoRespaldo::findOrFail($id);
        $doc->update($datos);
        return $doc;
    }

    public function eliminar(int $id): bool
    {
        $doc = DocumentoRespaldo::findOrFail($id);
        return (bool) $doc->delete();
    }
}
