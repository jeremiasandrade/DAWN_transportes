<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    public const METODOS_PAGO = [
        'mercado_pago' => [
            'etiqueta' => 'Mercado Pago',
            'detalle' => 'Saldo o tarjeta',
        ],
        'tarjeta_credito' => [
            'etiqueta' => 'Tarjeta de crédito',
            'detalle' => 'Hasta 3 cuotas sin interés',
        ],
        'tarjeta_debito' => [
            'etiqueta' => 'Tarjeta de débito',
            'detalle' => 'Pago inmediato',
        ],
        'transferencia' => [
            'etiqueta' => 'Transferencia bancaria',
            'detalle' => 'Transferencia o depósito',
        ],
    ];

    public const ESTADOS = [
        'pendiente' => 'Pendiente',
        'aprobado' => 'Aprobado',
        'rechazado' => 'Rechazado',
    ];

    protected $fillable = [
        'user_id',
        'referencia',
        'viaje_codigo',
        'origen',
        'destino',
        'monto',
        'metodo_pago',
        'estado',
        'nombre_pasajero',
        'dni_pasajero',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMetodoPagoEtiquetaAttribute(): string
    {
        return self::METODOS_PAGO[$this->metodo_pago]['etiqueta'] ?? $this->metodo_pago;
    }

    public function getEstadoEtiquetaAttribute(): string
    {
        return self::ESTADOS[$this->estado] ?? $this->estado;
    }
}
