@extends('layouts.app')

@section('title', 'Inicio')

@section('content')

<h3 class="mb-4">Panel Principal</h3>

<div class="row g-4">

    <!-- SOCIOS -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <i class="bi bi-person-badge fs-1 text-primary"></i>
                <h5 class="mt-2">Socios</h5>
                <p class="text-muted">Registro y control</p>
                <a href="{{ route('admin.socio.index') }}" class="btn btn-primary btn-sm">
                    Gestionar
                </a>
            </div>
        </div>
    </div>

    <!-- BENEFICIARIOS -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <i class="bi bi-people fs-1 text-success"></i>
                <h5 class="mt-2">Beneficiarios</h5>
                <p class="text-muted">Familiares registrados</p>
                <a href="{{ route('beneficiarios.index') }}" class="btn btn-success btn-sm">
                    Gestionar
                </a>
            </div>
        </div>
    </div>

    <!-- DOCUMENTOS -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <i class="bi bi-folder2-open fs-1 text-warning"></i>
                <h5 class="mt-2">Documentos</h5>
                <p class="text-muted">Archivos de respaldo</p>
                <a href="#" class="btn btn-warning btn-sm">
                    Ver documentos
                </a>
            </div>
        </div>
    </div>

    <!-- CREDENCIALES -->
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center">
                <i class="bi bi-credit-card-2-front fs-1 text-danger"></i>
                <h5 class="mt-2">Credenciales</h5>
                <p class="text-muted">Emisión y control</p>
                <a href="#" class="btn btn-danger btn-sm">
                    Administrar
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
