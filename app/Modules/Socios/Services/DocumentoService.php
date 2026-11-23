<?php

namespace App\Modules\Socios\Services;

use App\Modules\Socios\Contracts\DocumentoRepositoryInterface;
use App\Models\DocumentoRespaldo;
use App\Shared\Services\FileUploaderService;
use Illuminate\Http\UploadedFile;

class DocumentoService
{
    public function __construct(
        protected DocumentoRepositoryInterface $documentoRepo,
        protected FileUploaderService $fileUploader
    ) {}

    public function subirDocumento(array $datos, UploadedFile $archivo): DocumentoRespaldo
    {
        $base = ($datos['tipo_documento'] ?? 'documento') . '_' . time();
        $datos['archivo'] = $this->fileUploader->subir($archivo, 'documentos', $base);
        return $this->documentoRepo->crear($datos);
    }

    public function actualizarDocumento(int $id, array $datos, ?UploadedFile $archivo = null): DocumentoRespaldo
    {
        $doc = $this->documentoRepo->buscarPorId($id);

        if ($archivo) {
            $this->fileUploader->eliminar($doc->archivo);
            $base = ($datos['tipo_documento'] ?? 'documento') . '_' . time();
            $datos['archivo'] = $this->fileUploader->subir($archivo, 'documentos', $base);
        }

        return $this->documentoRepo->actualizar($id, $datos);
    }

    public function eliminarDocumento(int $id): bool
    {
        $doc = $this->documentoRepo->buscarPorId($id);
        if ($doc) {
            $this->fileUploader->eliminar($doc->archivo);
            return $this->documentoRepo->eliminar($id);
        }
        return false;
    }
}
