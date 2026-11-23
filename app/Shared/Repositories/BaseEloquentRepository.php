<?php

namespace App\Shared\Repositories;

use App\Shared\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Repositorio abstracto base reutilizable por todos los módulos
 */
abstract class BaseEloquentRepository implements BaseRepositoryInterface
{
    /**
     * Retorna una nueva instancia del modelo Eloquent correspondiente
     */
    abstract protected function getModel(): Model;

    public function all(): Collection
    {
        return $this->getModel()->all();
    }

    public function find(int $id): ?Model
    {
        return $this->getModel()->find($id);
    }

    public function create(array $data): Model
    {
        return $this->getModel()->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $registro = $this->getModel()->findOrFail($id);
        $registro->update($data);
        return $registro;
    }

    public function delete(int $id): bool
    {
        $registro = $this->getModel()->findOrFail($id);
        return (bool) $registro->delete();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return $this->getModel()->latest()->paginate($perPage);
    }
}
