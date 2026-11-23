<?php

namespace App\Modules\Cajas\Contracts;

use App\Shared\Contracts\BaseRepositoryInterface;
use App\Models\Persona;
use Illuminate\Support\Collection;

interface PersonaRepositoryInterface extends BaseRepositoryInterface
{
    public function search(string $term, int $limit = 10): Collection;
    public function findById(int $id): ?Persona;
    public function buscarPorCiONombre(string $termino, int $limite = 15): Collection;
    public function buscarPorId(int $id): ?Persona;
    public function crearParticular(object $dto): Persona;
    public function createParticular(array $datosPersona, string $codigoParticular): Persona;
}
