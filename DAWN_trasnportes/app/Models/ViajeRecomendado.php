<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViajeRecomendado extends Model
{
    protected $table = 'viajes_recomendados';

    protected $fillable = [
        'titulo',
        'ruta',
        'viaje_id',
        'imagen',
        'precio',
        'descuento_porcentaje',
        'vehiculo',
        'activo',
        'orden',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'descuento_porcentaje' => 'integer',
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    public function viaje(): BelongsTo
    {
        return $this->belongsTo(Viaje::class, 'viaje_id', 'id_viaje');
    }

    public function getPrecioFinalAttribute(): ?string
    {
        if ($this->precio === null) {
            return null;
        }

        $precio = (float) $this->precio;
        if ($this->descuento_porcentaje > 0) {
            $precio = $precio * (1 - $this->descuento_porcentaje / 100);
        }

        return '$'.number_format($precio, 0, ',', '.');
    }
}
