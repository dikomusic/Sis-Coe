<?php

namespace App\Modules\Cajas\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarReprogramacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tarjeta_inscripcion_id' => 'required|integer|exists:tarjetas_inscripcion,id',
            'nueva_fecha_vencimiento' => 'required|date|after:today',
            'motivo' => 'required|string|max:255',
            'autorizado_por' => 'required|string|max:150',
        ];
    }

    public function messages(): array
    {
        return [
            'tarjeta_inscripcion_id.required' => 'Debe indicar la tarjeta a reprogramar.',
            'nueva_fecha_vencimiento.required' => 'La nueva fecha de vencimiento es obligatoria.',
            'nueva_fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior al día de hoy.',
            'motivo.required' => 'El motivo de la reprogramación es obligatorio.',
            'autorizado_por.required' => 'Debe especificar quién autoriza la reprogramación.',
        ];
    }
}
