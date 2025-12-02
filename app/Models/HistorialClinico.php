<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HistorialClinico extends Model
{
    protected $table = 'historial_clinico';
    protected $primaryKey = 'id_historial';
    protected $fillable = ['id_cita','diagnostico','tratamiento','observaciones','fecha_registro'];

    public function cita()
    {
        return $this->belongsTo(Cita::class, 'id_cita', 'id_cita');
    }
}
