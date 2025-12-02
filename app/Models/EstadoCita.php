<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EstadoCita extends Model
{
    protected $table = 'estado_cita';
    protected $primaryKey = 'id_estado';
    protected $fillable = ['estado'];

    public function citas()
    {
        return $this->hasMany(Cita::class, 'id_estado', 'id_estado');
    }
}
