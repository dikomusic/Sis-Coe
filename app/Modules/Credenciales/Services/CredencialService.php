<?php

namespace App\Modules\Credenciales\Services;

use App\Modules\Credenciales\Contracts\CredencialRepositoryInterface;
use App\Models\Credencial;
use App\Models\SocioTitular;
use App\Models\BeneficiarioTitular;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

class CredencialService
{
    public function __construct(
        protected CredencialRepositoryInterface $credencialRepo
    ) {}

    public function emitirCredencial(string $tipo, int $id, array $datos, ?UploadedFile $imgFrontal = null, ?UploadedFile $imgTrasera = null): Credencial
    {
        $model = $tipo === 'socio' || $tipo === 'titular'
            ? SocioTitular::findOrFail($id)
            : BeneficiarioTitular::findOrFail($id);

        $emision = Carbon::parse($datos['fecha_emision']);
        $vencimiento = $this->calcularVencimiento($model, $tipo, $emision);

        $payload = [
            'entidad_type'      => ($tipo === 'socio' || $tipo === 'titular') ? SocioTitular::class : BeneficiarioTitular::class,
            'entidad_id'        => $id,
            'fecha_emision'     => $emision,
            'fecha_vencimiento' => $vencimiento,
            'estado'            => $datos['estado'] ?? 'activo',
            'estado_credencial' => $datos['estado_credencial'] ?? 'EMITIDA',
        ];

        if ($imgFrontal) {
            $payload['imagen_frontal'] = $imgFrontal->store('credenciales', 'public');
        }
        if ($imgTrasera) {
            $payload['imagen_trasera'] = $imgTrasera->store('credenciales', 'public');
        }

        return $this->credencialRepo->crear($payload);
    }

    protected function calcularVencimiento(object $model, string $tipo, Carbon $emision): Carbon
    {
        if ($tipo === 'socio' || $tipo === 'titular') {
            if (strtoupper($model->situacion_actual ?? '') === 'PASIVO') {
                return Carbon::create(2099, 12, 31);
            }

            if (!empty($model->fecha_alta_ejercito)) {
                $fechaAlta = Carbon::parse($model->fecha_alta_ejercito);
                $aniosTranscurridos = $fechaAlta->diffInYears($emision);
                $bloque = floor($aniosTranscurridos / 5);
                return $fechaAlta->copy()->addYears(($bloque + 1) * 5);
            }

            return $emision->copy()->addYears(5);
        }

        // Beneficiarios
        $vencimiento = $emision->copy()->addYears(5);

        if (in_array(strtoupper($model->parentesco ?? ''), ['HIJO', 'HIJA', 'HIJASTRO', 'HIJASTRA']) && !empty($model->fecha_nacimiento)) {
            $cumple25 = Carbon::parse($model->fecha_nacimiento)->addYears(25);
            if ($vencimiento->gt($cumple25)) {
                $vencimiento = $cumple25;
            }
        }

        return $vencimiento;
    }
}
