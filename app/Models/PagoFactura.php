<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoFactura extends Model
{
    protected $table = 'pagos_facturas';

    protected $fillable = [
        'inscripcion_id',
        'numero_factura',
        'importe_base',
        'porcentaje_descuento',
        'monto_descuento',
        'importe_final',
        'tipo_beneficio',
        'motivo_descuento',
        'autorizacion_descuento',
        'fecha_pago',
        'cajero_id'
    ];

    protected $casts = [
        'importe_base' => 'decimal:2',
        'porcentaje_descuento' => 'decimal:2',
        'monto_descuento' => 'decimal:2',
        'importe_final' => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id');
    }

    public function cajero()
    {
        return $this->belongsTo(User::class, 'cajero_id');
    }
}

