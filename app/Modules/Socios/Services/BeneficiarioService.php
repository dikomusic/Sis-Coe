<?php

namespace App\Modules\Socios\Services;

use App\Modules\Socios\Contracts\BeneficiarioRepositoryInterface;
use App\Models\BeneficiarioTitular;
use App\Shared\Services\FileUploaderService;
use Illuminate\Http\UploadedFile;

class BeneficiarioService
{
    public function __construct(
        protected BeneficiarioRepositoryInterface $beneficiarioRepo,
        protected FileUploaderService $fileUploader
    ) {}

    public function registrarBeneficiario(array $datos, ?UploadedFile $foto = null): BeneficiarioTitular
    {
        if ($foto) {
            $nombreBase = ($datos['nombres'] ?? '') . '_' . ($datos['paterno'] ?? '');
            $datos['foto'] = $this->fileUploader->subir($foto, 'beneficiarios', $nombreBase);
        }

        return $this->beneficiarioRepo->crear($datos);
    }

    public function actualizarBeneficiario(int $id, array $datos, ?UploadedFile $foto = null, bool $borrarFoto = false): BeneficiarioTitular
    {
        $beneficiario = $this->beneficiarioRepo->buscarPorId($id);

        if ($foto) {
            $this->fileUploader->eliminar($beneficiario->foto);
            $nombreBase = ($datos['nombres'] ?? $beneficiario->nombres) . '_' . ($datos['paterno'] ?? $beneficiario->paterno);
            $datos['foto'] = $this->fileUploader->subir($foto, 'beneficiarios', $nombreBase);
        } elseif ($borrarFoto) {
            $this->fileUploader->eliminar($beneficiario->foto);
            $datos['foto'] = null;
        }

        return $this->beneficiarioRepo->actualizar($id, $datos);
    }

    public function eliminarBeneficiario(int $id): bool
    {
        $beneficiario = $this->beneficiarioRepo->buscarPorId($id);
        if ($beneficiario) {
            $this->fileUploader->eliminar($beneficiario->foto);
            return $this->beneficiarioRepo->eliminar($id);
        }
        return false;
    }
}
