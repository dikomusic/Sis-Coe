<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
/**
     * Muestra el dashboard del administrador
     */
    public function index()
    {
        return view('dashboard'); // o la vista que uses
    }
}
