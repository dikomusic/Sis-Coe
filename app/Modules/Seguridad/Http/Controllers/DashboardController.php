<?php

namespace App\Modules\Seguridad\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SocioTitular;
use App\Models\BeneficiarioTitular;
use App\Models\Credencial;
use App\Models\PagoFactura;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function admin(): View
    {
        $stats = Cache::remember('admin_dashboard_stats', 45, function () {
            return [
                'total_socios' => SocioTitular::count(),
                'total_beneficiarios' => BeneficiarioTitular::count(),
                'recaudacion_hoy' => (float) (PagoFactura::whereDate('created_at', now()->toDateString())->sum('importe_final') ?? 0),
                'credenciales_activas' => Credencial::where('estado', 'activo')->count(),
                'inscripciones_hoy' => Inscripcion::whereDate('created_at', now()->toDateString())->count(),
            ];
        });

        $ultimosSocios = SocioTitular::latest()->limit(5)->get();
        $ultimosPagos = PagoFactura::with(['inscripcion.persona', 'inscripcion.horarioCurso.disciplina'])
            ->latest()
            ->limit(5)
            ->get();

        return view('pages.dashboard.admin', compact('stats', 'ultimosSocios', 'ultimosPagos'));
    }

    public function usuario(): View
    {
        return view('pages.dashboard.usuario');
    }

    public function invitado(): View
    {
        return view('pages.dashboard.invitado');
    }
}
