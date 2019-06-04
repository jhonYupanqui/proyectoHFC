<?php

namespace App\Administrador;

use App\Administrador\Role;
use App\Transformers\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    const ESTADO_ACTIVO = "A";
    const ESTADO_INACTIVO = "I";

    public $transformer = UserTransformer::class;
    
    protected $table = 'users';

    protected $fillable = [
        'empresa_id',
        'role_id',
        'nombre',
        'apellidos',
        'dni',
        'telefono',
        'email',
        'username',
        'password',
        'estado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    public function empresa(){
        return $this->belongsTo(Empresa::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function permisos(){
        return $this->belongsToMany(Permiso::class);
    }
 
}
