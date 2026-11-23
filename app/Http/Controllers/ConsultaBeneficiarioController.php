<?php

namespace App\Http\Controllers;

use App\Models\BeneficiarioTitular;
use Illuminate\Http\Request;

class ConsultaBeneficiarioController extends Controller
{
    public function buscar(Request $request)
    {
        $beneficiarios = BeneficiarioTitular::query()
            ->when($request->q, function ($q) use ($request) {
                $q->where('ci', 'like', "%{$request->q}%")
                  ->orWhere('nombres', 'like', "%{$request->q}%")
                  ->orWhere('paterno', 'like', "%{$request->q}%")
                  ->orWhere('materno', 'like', "%{$request->q}%");
            })
            ->with('titular')
            ->paginate(15);

        return view('consultas.search', compact('beneficiarios'));
    }

    public function show($id)
    {
        $beneficiario = BeneficiarioTitular::with('titular')->findOrFail($id);
        return view('consultas.detalle', ['entidad' => $beneficiario, 'tipo' => 'BENEFICIARIO']);
    }
}
