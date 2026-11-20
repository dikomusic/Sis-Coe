<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Credencial;
use App\Models\SocioTitular;
use App\Models\BeneficiarioTitular;

class ConsultaGeneralController extends Controller
{
    public function index()
    {
        return view('consultas.index');
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'codigo' => 'required'
        ]);

        $credencial = Credencial::where('id', $request->codigo)
            ->latest('fecha_emision')
            ->first();

        $entidad      = null;
        $mensajeError = null;

        if ($credencial) {
            $entidad = $credencial->entidad;

            if ($credencial->estado !== 'activo') {
                $mensajeError = 'La credencial está INACTIVA. Motivo: '
                    . ($credencial->estado_credencial ?? 'No especificado');
            }
        } else {
            $mensajeError = 'Credencial INEXISTENTE o no encontrada con el código ingresado.';
        }

        return view('consultas.resultado', compact('credencial', 'entidad', 'mensajeError'));
    }

    public function search(Request $request)
    {
        $mode = $request->input('mode', 'TODOS');
        $tipo = $request->input('tipo', 'SOCIO');
        $q    = trim($request->input('q'));

        $entidad       = null;
        $credencial    = null;
        $mensajeError  = null;
        $socios        = collect();
        $beneficiarios = collect();

        if ($mode === 'ID') {

            if ($tipo === 'SOCIO') {
                // PK de socios_titulares es id_titular
                $entidad = SocioTitular::where('id_titular', $q)->first();
            } else {
                $entidad = BeneficiarioTitular::find($q);
            }

            if ($entidad) {
                // Usamos la PK real del modelo para buscar en credenciales
                $pkValue = $tipo === 'SOCIO'
                    ? $entidad->id_titular
                    : $entidad->getKey();

                $credencial = Credencial::where('entidad_id', $pkValue)
                    ->where('entidad_type', get_class($entidad))
                    ->latest('fecha_emision')
                    ->first();

                if (!$credencial) {
                    $mensajeError = 'Este '
                        . ($tipo === 'SOCIO' ? 'socio' : 'beneficiario')
                        . ' no tiene ninguna credencial generada.';
                }
            } else {
                $mensajeError = 'ID no encontrado en '
                    . ($tipo === 'SOCIO' ? 'socios' : 'beneficiarios') . '.';
            }

            return view('consultas.detalle', compact('entidad', 'credencial', 'mensajeError'));
        }

        if ($mode === 'TODOS') {

            if ($tipo === 'SOCIO') {
                $socios = SocioTitular::where(function ($query) use ($q) {
                    $query->where('ci', 'like', "%{$q}%")
                        ->orWhere('nombres', 'like', "%{$q}%")
                        ->orWhere(DB::raw("CONCAT(paterno,' ',materno)"), 'like', "%{$q}%")
                        ->orWhere(DB::raw("CONCAT(nombres,' ',paterno,' ',materno)"), 'like', "%{$q}%");
                })->paginate(15);
            }

            if ($tipo === 'BENEFICIARIO') {
                $beneficiarios = BeneficiarioTitular::where(function ($query) use ($q) {
                    $query->where('ci', 'like', "%{$q}%")
                        ->orWhere('nombres', 'like', "%{$q}%")
                        ->orWhere(DB::raw("CONCAT(paterno,' ',materno)"), 'like', "%{$q}%")
                        ->orWhere(DB::raw("CONCAT(nombres,' ',paterno,' ',materno)"), 'like', "%{$q}%");
                })->paginate(15);
            }

            return view('consultas.socio.buscar', compact('socios', 'beneficiarios', 'mode', 'tipo', 'q'));
        }
    }

    public function detalle($id, $tipo)
    {
        $entidad    = null;
        $credencial = null;

        if ($tipo === 'socio') {
            // CORREGIDO: busca por id_titular que es la PK real
            $entidad = SocioTitular::where('id_titular', $id)->firstOrFail();

            $credencial = Credencial::where('entidad_id', $entidad->id_titular)
                ->where('entidad_type', SocioTitular::class)
                ->latest('fecha_emision')
                ->first();
        } else {
            $entidad = BeneficiarioTitular::findOrFail($id);

            $credencial = Credencial::where('entidad_id', $entidad->getKey())
                ->where('entidad_type', BeneficiarioTitular::class)
                ->latest('fecha_emision')
                ->first();
        }

        $mensajeError = null;
        if (!$credencial) {
            $mensajeError = 'Este ' . $tipo . ' no tiene ninguna credencial generada.';
        }

        return view('consultas.detalle', compact('entidad', 'credencial', 'mensajeError'));
    }
}