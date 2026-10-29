<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Persona extends Model
{
    protected $table = 'personas';

    protected $primaryKey = 'dni';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected $fillable = [
        'dni',
        'nombre',
        'segundo_nombre',
        'apellidos',
        'correo_electronico',
        'contrasenia',
        'num_telefono',
        'sexo',
    ];

    protected $hidden = [
        'contrasenia',
    ];

    public function cliente(): HasOne
    {
        return $this->hasOne(Cliente::class, 'persona_dni', 'dni');
    }

    public function pasajes(): HasMany
    {
        return $this->hasMany(Pasaje::class, 'persona_dni', 'dni');
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombre.' '.$this->apellidos);
    }
}
