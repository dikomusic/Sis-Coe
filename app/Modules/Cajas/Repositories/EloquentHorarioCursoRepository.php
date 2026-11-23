<?php

namespace App\Modules\Cajas\Repositories;

use App\Shared\Repositories\BaseEloquentRepository;
use App\Models\Disciplina;
use App\Models\HorarioCurso;
use App\Modules\Cajas\Contracts\HorarioCursoRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EloquentHorarioCursoRepository extends BaseEloquentRepository implements HorarioCursoRepositoryInterface
{
    protected function getModel(): Model
    {
        return new HorarioCurso();
    }

    public function getActivosConRelaciones(): Collection
    {
        return HorarioCurso::with(['disciplina', 'instructor'])
            ->where('activo', true)
            ->get();
    }

    public function getDisciplinasConModalidadesYHorarios(): Collection
    {
        return Disciplina::where('activo', true)
            ->with([
                'modalidades' => fn($q) => $q->where('activo', true)->with('tarifas'),
                'horarios' => fn($q) => $q->where('activo', true)->with('instructor')
            ])
            ->get();
    }

    public function findById(int $id): ?HorarioCurso
    {
        return HorarioCurso::with(['disciplina', 'modalidad', 'instructor'])->find($id);
    }

    public function obtenerPorDisciplina(int $disciplinaId): Collection
    {
        return HorarioCurso::where('disciplina_id', $disciplinaId)
            ->where('activo', true)
            ->with(['instructor.persona', 'modalidad'])
            ->get();
    }
}
