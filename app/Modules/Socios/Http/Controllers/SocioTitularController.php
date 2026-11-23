<?php

namespace App\Modules\Socios\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Socios\Contracts\SocioRepositoryInterface;
use App\Modules\Socios\Services\SocioService;
use App\Models\SocioTitular;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\Response;

class SocioTitularController extends Controller
{
    public function __construct(
        protected SocioRepositoryInterface $socioRepo,
        protected SocioService $socioService
    ) {}

    public function index(Request $request): View
    {
        $socios = $this->socioRepo->listarConFiltros($request->all(), 15);
        $socios->appends($request->query());

        return view('pages.socios.index', compact('socios'));
    }

    public function create(): View
    {
        return view('pages.socios.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nombres'           => 'required|string|max:255',
            'paterno'           => 'required|string|max:255',
            'materno'           => 'nullable|string|max:255',
            'ci'                => 'required|string|max:50',
            'grado'             => 'required|string',
            'arma_especialidad' => 'required|string',
            'arma_especialidad_manual' => 'nullable|string|max:100|required_if:arma_especialidad,OTRO',
            'foto'              => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $this->socioService->registrarSocio($request->all(), $request->file('foto'));

        return redirect()->route('admin.socio.index')->with('success', 'Socio creado correctamente');
    }

    public function edit(int $id): View
    {
        $socio = $this->socioRepo->buscarPorId($id);
        if (!$socio) {
            abort(404, 'Socio no encontrado');
        }
        return view('pages.socios.edit', compact('socio'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'nombres' => 'required|string|max:255',
            'paterno' => 'required|string|max:255',
            'materno' => 'nullable|string|max:255',
            'foto'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $this->socioService->actualizarSocio(
            $id, 
            $request->all(), 
            $request->file('foto'),
            $request->has('borrar_foto')
        );

        return redirect()->route('admin.socio.index')->with('success', 'Socio actualizado correctamente');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->socioService->eliminarSocio($id);

        return redirect()->route('admin.socio.index')->with('success', 'Socio eliminado correctamente');
    }

    public function ficha(int $id): View
    {
        $socio = $this->socioRepo->buscarPorId($id);
        if (!$socio) {
            abort(404, 'Socio no encontrado');
        }
        return view('pages.socios.ficha', compact('socio'));
    }

    public function fichaPdf(int $id): Response
    {
        $socio = $this->socioRepo->buscarPorId($id);
        if (!$socio) {
            abort(404, 'Socio no encontrado');
        }

        $pdf = Pdf::loadView('pages.socios.ficha_pdf', compact('socio'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('ficha_socio_' . $socio->id_titular . '.pdf');
    }
}
