<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctor';
    protected $primaryKey = 'id_doctor';

    protected $fillable = [
        'nombre_doctor',
        'telefono',
        'correo',
        'id_especialidad',
        'id_usuario'
    ];

    // RELACIÓN: Doctor pertenece a una especialidad
    public function especialidad()
    {
        return $this->belongsTo(Especialidad::class, 'id_especialidad', 'id_especialidad');
    }

    // RELACIÓN: Doctor pertenece a un usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    // RELACIÓN: Un doctor puede tener muchas citas
    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_doctor', 'id_doctor');
    }
}
