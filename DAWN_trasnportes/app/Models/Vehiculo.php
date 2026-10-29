<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehiculo extends Model
{
    protected $table = 'vehiculos';

    protected $primaryKey = 'id_vehiculo';

    public $timestamps = false;

    protected $fillable = [
        'modelo',
        'marca',
        'patente',
        'chofer_dni',
    ];

    public function viajes(): HasMany
    {
        return $this->hasMany(Viaje::class, 'vehiculo_id', 'id_vehiculo');
    }
}
