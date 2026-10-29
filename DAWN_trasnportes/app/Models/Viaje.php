<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Viaje extends Model
{
    protected $table = 'viajes';

    protected $primaryKey = 'id_viaje';

    public $timestamps = false;

    protected $fillable = [
        'id_ciudad_partida',
        'id_ciudad_llegada',
        'cliente_dni',
        'vehiculo_id',
        'fecha_partida',
        'fecha_llegada',
    ];

    protected function casts(): array
    {
        return [
            'fecha_partida' => 'datetime',
            'fecha_llegada' => 'datetime',
        ];
    }

    public function ciudadPartida(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad_partida', 'id_ciudad');
    }

    public function ciudadLlegada(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad_llegada', 'id_ciudad');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_dni', 'persona_dni');
    }

    public function vehiculo(): BelongsTo
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id', 'id_vehiculo');
    }

    public function pasajes(): HasMany
    {
        return $this->hasMany(Pasaje::class, 'viaje_id', 'id_viaje');
    }
}
