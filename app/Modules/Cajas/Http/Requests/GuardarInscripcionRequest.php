<?php

namespace App\Modules\Cajas\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarInscripcionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'persona_id' => 'required|integer|exists:personas,id',
            'horario_curso_id' => 'required|integer|exists:horarios_cursos,id',
            'tarifa_id' => 'required|integer|exists:tarifas,id',
            'fecha_inicio' => 'required|date',
            'numero_factura' => 'required|string|max:50',
            'importe_base' => 'required|numeric|min:0',
            'descuento_porcentaje' => 'nullable|numeric|min:0|max:100',
            'motivo_descuento' => 'nullable|string|max:255',
            'autorizacion_descuento' => 'nullable|string|max:255',
            'importe_final' => 'required|numeric|min:0',
            'tipo_beneficio' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'persona_id.required' => 'Debe seleccionar una persona (socio, beneficiario o particular).',
            'horario_curso_id.required' => 'Debe seleccionar una disciplina y horario.',
            'tarifa_id.required' => 'Debe seleccionar una tarifa válida.',
            'numero_factura.required' => 'El número de factura o recibo es obligatorio.',
            'fecha_inicio.required' => 'La fecha de inicio es requerida.',
        ];
    }
}
