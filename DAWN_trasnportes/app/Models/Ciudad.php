<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    protected $table = 'ciudades';

    protected $primaryKey = 'id_ciudad';

    public $timestamps = false;

    protected $fillable = [
        'nombre_ciudad',
        'departamento',
        'provincia_id',
        'latitud',
        'longitud',
    ];

    protected function casts(): array
    {
        return [
            'id_ciudad' => 'integer',
            'provincia_id' => 'integer',
            'latitud' => 'float',
            'longitud' => 'float',
        ];
    }

    public function getEtiquetaAttribute(): string
    {
        $ubicacion = collect([$this->departamento, $this->provincia?->nombre_provincia])
            ->filter()
            ->implode(', ');

        return $ubicacion === ''
            ? $this->nombre_ciudad
            : "{$this->nombre_ciudad} ({$ubicacion})";
    }

    public function tieneCoordenadas(): bool
    {
        return $this->latitud !== null && $this->longitud !== null;
    }

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'provincia_id', 'id_provincia');
    }

    public function rutasOrigen(): HasMany
    {
        return $this->hasMany(Ruta::class, 'id_ciudad_partida', 'id_ciudad');
    }

    public function rutasDestino(): HasMany
    {
        return $this->hasMany(Ruta::class, 'id_ciudad_llegada', 'id_ciudad');
    }

    public function viajesOrigen(): HasMany
    {
        return $this->hasMany(Viaje::class, 'id_ciudad_partida', 'id_ciudad');
    }

    public function viajesDestino(): HasMany
    {
        return $this->hasMany(Viaje::class, 'id_ciudad_llegada', 'id_ciudad');
    }
}
