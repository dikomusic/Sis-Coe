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

    // Busca la credencial (puedes quitar el filtro de 'activo' si quieres mostrar inactivas)
    $credencial = Credencial::where('id', $request->codigo)->first();

    $entidad = null;
    $mensajeError = null;

    if ($credencial) {
        $entidad = $credencial->entidad;

        // Puedes pasar un flag si está inactiva
        if ($credencial->estado !== 'activo') {
            $mensajeError = 'La credencial está INACTIVA. Motivo: ' . ($credencial->estado_credencial ?? 'No especificado');
        }
    } else {
        $mensajeError = 'Credencial INEXISTENTE o no encontrada con el código ingresado.';
    }

    // Siempre devuelve la vista, incluso si $credencial es null
    return view('consultas.resultado', compact('credencial', 'entidad', 'mensajeError'));
}

public function search(Request $request)
{
    $mode = $request->input('mode', 'TODOS');
    $tipo = $request->input('tipo', 'SOCIO');
    $q = trim($request->input('q'));

    $entidad = null;
    $credencial = null;
    $mensajeError = null;

    $socios = collect();
    $beneficiarios = collect();

    if ($mode === 'ID') {
        // Búsqueda directa por ID
        if ($tipo === 'SOCIO') {
            $entidad = SocioTitular::find($q);
        } else {
            $entidad = BeneficiarioTitular::find($q);
        }

        if ($entidad) {
            // Carga la credencial asociada
            $credencial = Credencial::where('entidad_id', $entidad->id)
                                   ->where('entidad_type', get_class($entidad))
                                   ->first();
        } else {
            $mensajeError = 'ID no encontrado en ' . ($tipo === 'SOCIO' ? 'socios' : 'beneficiarios');
        }

        // Siempre devuelve la vista detalle con las 3 variables
        return view('consultas.detalle', compact('entidad', 'credencial', 'mensajeError'));
    }



    if ($mode === 'TODOS') {

        if ($tipo === 'SOCIO') {
            $socios = SocioTitular::where(function($query) use ($q) {
                $query->where('ci', 'like', "%{$q}%")
                    ->orWhere('nombres', 'like', "%{$q}%")
                    ->orWhere(DB::raw("CONCAT(paterno,' ',materno)"), 'like', "%{$q}%")
                    ->orWhere(DB::raw("CONCAT(nombres,' ',paterno,' ',materno)"), 'like', "%{$q}%");
            })->paginate(15);
        }

        if ($tipo === 'BENEFICIARIO') {
            $beneficiarios = BeneficiarioTitular::where(function($query) use ($q) {
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
    $entidad = null;
    $credencial = null;

    if ($tipo === 'socio') {
        $entidad = SocioTitular::findOrFail($id);
        // Busca la credencial asociada al socio
        $credencial = Credencial::where('entidad_id', $id)
                               ->where('entidad_type', SocioTitular::class)
                               ->first();
    } else {
        $entidad = BeneficiarioTitular::findOrFail($id);
        // Busca la credencial asociada al beneficiario
        $credencial = Credencial::where('entidad_id', $id)
                               ->where('entidad_type', BeneficiarioTitular::class)
                               ->first();
    }

    return view('consultas.detalle', compact('entidad', 'credencial'));
}
}