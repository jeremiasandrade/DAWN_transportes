<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ruta extends Model
{
    protected $table = 'ciudades_has_ciudades';

    protected $primaryKey = 'id_ciudad_partida';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id_ciudad_partida',
        'id_ciudad_llegada',
        'distancia',
    ];

    protected function casts(): array
    {
        return [
            'id_ciudad_partida' => 'integer',
            'id_ciudad_llegada' => 'integer',
            'distancia' => 'integer',
        ];
    }

    public function origen(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad_partida', 'id_ciudad');
    }

    public function destino(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'id_ciudad_llegada', 'id_ciudad');
    }

    public function getRouteKey(): string
    {
        return $this->id_ciudad_partida.'-'.$this->id_ciudad_llegada;
    }

    public function resolveRouteBinding($value, $field = null): ?Model
    {
        [$partida, $llegada] = array_pad(explode('-', (string) $value, 2), 2, null);

        if ($partida === null || $llegada === null) {
            return null;
        }

        return $this->newQuery()
            ->where('id_ciudad_partida', $partida)
            ->where('id_ciudad_llegada', $llegada)
            ->first();
    }

    public function getClaveAttribute(): string
    {
        return $this->getRouteKey();
    }
}
