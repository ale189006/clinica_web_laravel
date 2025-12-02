<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    protected $table = 'especialidad';
    protected $primaryKey = 'id_especialidad';
    protected $fillable = ['especialidad'];

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'id_especialidad', 'id_especialidad');
    }

    // Alias para compatibilidad
    public function doctores()
    {
        return $this->doctors();
    }
}
