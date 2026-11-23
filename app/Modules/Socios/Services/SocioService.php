<?php

namespace App\Modules\Socios\Services;

use App\Modules\Socios\Contracts\SocioRepositoryInterface;
use App\Models\SocioTitular;
use App\Shared\Services\FileUploaderService;
use Illuminate\Http\UploadedFile;

class SocioService
{
    public function __construct(
        protected SocioRepositoryInterface $socioRepo,
        protected FileUploaderService $fileUploader
    ) {}

    public function registrarSocio(array $datos, ?UploadedFile $foto = null): SocioTitular
    {
        if (($datos['arma_especialidad'] ?? '') === 'OTRO' && !empty($datos['arma_especialidad_manual'])) {
            $datos['arma_especialidad'] = strtoupper(trim($datos['arma_especialidad_manual']));
        }

        if ($foto) {
            $nombreBase = ($datos['nombres'] ?? '') . '_' . ($datos['paterno'] ?? '') . '_' . ($datos['materno'] ?? '');
            $datos['foto'] = $this->fileUploader->subir($foto, 'socios', $nombreBase);
        }

        return $this->socioRepo->crear($datos);
    }

    public function actualizarSocio(int $id, array $datos, ?UploadedFile $foto = null, bool $borrarFoto = false): SocioTitular
    {
        $socio = $this->socioRepo->buscarPorId($id);

        if (($datos['arma_especialidad'] ?? '') === 'OTRO' && !empty($datos['arma_especialidad_manual'])) {
            $datos['arma_especialidad'] = strtoupper(trim($datos['arma_especialidad_manual']));
        }

        if ($foto) {
            $this->fileUploader->eliminar($socio->foto);
            $nombreBase = ($datos['nombres'] ?? $socio->nombres) . '_' . ($datos['paterno'] ?? $socio->paterno);
            $datos['foto'] = $this->fileUploader->subir($foto, 'socios', $nombreBase);
        } elseif ($borrarFoto) {
            $this->fileUploader->eliminar($socio->foto);
            $datos['foto'] = null;
        }

        return $this->socioRepo->actualizar($id, $datos);
    }

    public function eliminarSocio(int $id): bool
    {
        $socio = $this->socioRepo->buscarPorId($id);
        if ($socio) {
            $this->fileUploader->eliminar($socio->foto);
            return $this->socioRepo->eliminar($id);
        }
        return false;
    }
}
