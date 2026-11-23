<?php

namespace App\Modules\Cajas\Contracts;

use App\Shared\Contracts\BaseRepositoryInterface;
use App\Models\HorarioCurso;
use Illuminate\Support\Collection;

interface HorarioCursoRepositoryInterface extends BaseRepositoryInterface
{
    public function getActivosConRelaciones(): Collection;
    public function getDisciplinasConModalidadesYHorarios(): Collection;
    public function findById(int $id): ?HorarioCurso;
    public function obtenerPorDisciplina(int $disciplinaId): Collection;
}
