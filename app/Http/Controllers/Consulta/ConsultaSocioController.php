<?php

namespace App\Http\Controllers\Consulta;

use App\Http\Controllers\Controller;
use App\Models\SocioTitular;  // ← Asegúrate de que este sea el nombre correcto de tu modelo
use Illuminate\Http\Request;

class ConsultaSocioController extends Controller
{
    /**
     * Búsqueda de socios titulares
     */
    public function buscar(Request $request)
    {
        $socios = SocioTitular::query()
            ->when($request->q, function ($q) use ($request) {
                $q->where('ci', 'like', "%{$request->q}%")
                  ->orWhere('nombres', 'like', "%{$request->q}%")
                  ->orWhere('paterno', 'like', "%{$request->q}%")
                  ->orWhere('materno', 'like', "%{$request->q}%");
            })
            //->withCount('beneficiarios') // opcional: cuenta cuántos beneficiarios tiene
            
            ->paginate(15);

        return view('consultas.socio.buscar', compact('socios'));
    }

    /**
     * Mostrar detalle de un socio titular + sus beneficiarios
     */
    public function show($id)
    {
        $socio = SocioTitular::with([
            'beneficiarios',          // relación con beneficiarios
            'documentos',             // si tienes documentos asociados
            'credenciales'            // si tienes credenciales
        ])->findOrFail($id);

        return view('consultas.socio.show', compact('socio'));
    }
}