<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pasaje extends Model
{
    protected $table = 'pasajes';

    protected $fillable = [
        'viaje_id',
        'persona_dni',
        'numero_asiento',
        'precio',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'viaje_id' => 'integer',
            'numero_asiento' => 'integer',
            'precio' => 'decimal:2',
        ];
    }

    public function viaje(): BelongsTo
    {
        return $this->belongsTo(Viaje::class, 'viaje_id', 'id_viaje');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'persona_dni', 'dni');
    }
}
