<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    protected $fillable = ['usuario','correo','password','id_rol'];
    protected $hidden = ['password'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id_usuario';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id_usuario', 'id_usuario');
    }

    // Helper methods para verificar roles
    public function isAdmin()
    {
        return $this->rol && strtolower($this->rol->rol) === 'admin';
    }

    public function isDoctor()
    {
        return $this->rol && strtolower($this->rol->rol) === 'doctor';
    }

    public function isRecepcionista()
    {
        return $this->rol && strtolower($this->rol->rol) === 'recepcionista';
    }
}
