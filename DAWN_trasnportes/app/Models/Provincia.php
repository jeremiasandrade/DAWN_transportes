<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provincia extends Model
{
    protected $table = 'provincias';

    protected $primaryKey = 'id_provincia';

    public $timestamps = false;

    protected $fillable = [
        'nombre_provincia',
    ];

    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class, 'provincia_id', 'id_provincia');
    }
}
