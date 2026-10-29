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
        'provincia_id',
    ];

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
