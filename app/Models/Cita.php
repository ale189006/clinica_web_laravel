<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    protected $table = 'cita';
    protected $primaryKey = 'id_cita';
    protected $fillable = ['fecha','hora','id_doctor','id_paciente','motivo','id_estado'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'id_doctor', 'id_doctor');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente', 'id_paciente');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoCita::class, 'id_estado', 'id_estado');
    }

    public function historial()
    {
        return $this->hasOne(HistorialClinico::class, 'id_cita', 'id_cita');
    }
}
