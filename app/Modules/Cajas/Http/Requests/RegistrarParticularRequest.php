<?php

namespace App\Modules\Cajas\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrarParticularRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'ci' => 'required|string|max:25|unique:personas,ci',
            'complemento' => 'nullable|string|max:10',
            'expedido' => 'nullable|string|max:10',
            'fecha_nacimiento' => 'nullable|date',
            'sexo' => 'nullable|in:M,F',
            'telefono' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
            'contacto_emergencia_nombre' => 'nullable|string|max:150',
            'contacto_emergencia_telefono' => 'nullable|string|max:30',
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.required' => 'Los nombres son obligatorios.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'ci.required' => 'El Carnet de Identidad es obligatorio.',
            'ci.unique' => 'Ya existe una persona registrada con este número de CI.',
        ];
    }
}
