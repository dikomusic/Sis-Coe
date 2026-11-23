<?php

namespace App\Modules\Cajas\Repositories;

use App\Shared\Repositories\BaseEloquentRepository;
use App\Modules\Cajas\Contracts\PersonaRepositoryInterface;
use App\Models\Persona;
use App\Models\Particular;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class EloquentPersonaRepository extends BaseEloquentRepository implements PersonaRepositoryInterface
{
    protected function getModel(): Model
    {
        return new Persona();
    }

    public function search(string $term, int $limit = 10): Collection
    {
        return $this->buscarPorCiONombre($term, $limit);
    }

    public function findById(int $id): ?Persona
    {
        return $this->buscarPorId($id);
    }

    public function buscarPorCiONombre(string $termino, int $limite = 15): Collection
    {
        $terminoLimpo = trim($termino);
        $palabras = array_filter(explode(' ', preg_replace('/\s+/', ' ', $terminoLimpo)));

        return Persona::query()
            ->where(function($q) use ($terminoLimpo, $palabras) {
                $q->where('ci', 'like', "%{$terminoLimpo}%")
                  ->orWhereRaw("CONCAT_WS(' ', nombres, paterno, materno) ILIKE ?", ["%{$terminoLimpo}%"])
                  ->orWhereRaw("CONCAT_WS(' ', paterno, materno, nombres) ILIKE ?", ["%{$terminoLimpo}%"])
                  ->orWhereHas('inscripciones.tarjeta', function($sub) use ($terminoLimpo) {
                      $sub->where('codigo_tarjeta', 'ilike', "%{$terminoLimpo}%")
                          ->orWhere('codigo_qr', 'ilike', "%{$terminoLimpo}%");
                  })
                  ->orWhereHas('inscripciones', function($sub) use ($terminoLimpo) {
                      $sub->where('codigo_inscripcion', 'ilike', "%{$terminoLimpo}%");
                  });

                // Búsqueda multi-palabra: Si ingresa "Joaquin Muñoz" o "Daniela Antezana"
                if (count($palabras) > 1) {
                    $q->orWhere(function($subWords) use ($palabras) {
                        foreach ($palabras as $p) {
                            $subWords->where(function($pw) use ($p) {
                                $pw->where('ci', 'like', "%{$p}%")
                                   ->orWhereRaw("CONCAT_WS(' ', nombres, paterno, materno) ILIKE ?", ["%{$p}%"]);
                            });
                        }
                    });
                }
            })
            ->with(['socioTitular.grado', 'beneficiarioTitular', 'particular', 'inscripciones.tarjeta', 'inscripciones.horarioCurso.disciplina'])
            ->take($limite)
            ->get();
    }

    public function buscarPorId(int $id): ?Persona
    {
        return Persona::with(['socioTitular.grado', 'beneficiarioTitular', 'particular'])->find($id);
    }

    public function crearParticular(object $dto): Persona
    {
        $year = date('Y');
        $ultimoId = Particular::max('id') ?? 0;
        $codigoParticular = "PAR-{$year}-" . str_pad($ultimoId + 1, 4, '0', STR_PAD_LEFT);

        return $this->createParticular([
            'nombres' => $dto->nombres,
            'paterno' => $dto->apellidos,
            'ci' => $dto->ci,
            'telf_cel' => $dto->telefono ?? null,
        ], $codigoParticular);
    }

    public function createParticular(array $datosPersona, string $codigoParticular): Persona
    {
        $persona = Persona::create([
            'nombres' => strtoupper($datosPersona['nombres']),
            'paterno' => !empty($datosPersona['paterno']) ? strtoupper($datosPersona['paterno']) : null,
            'materno' => !empty($datosPersona['materno']) ? strtoupper($datosPersona['materno']) : null,
            'ci' => $datosPersona['ci'] ?? null,
            'telf_cel' => $datosPersona['telf_cel'] ?? null,
            'tipo_persona' => 'PARTICULAR'
        ]);

        Particular::create([
            'persona_id' => $persona->id,
            'codigo_particular' => $codigoParticular,
            'estado' => 'ACTIVO'
        ]);

        return $persona->load('particular');
    }
}
