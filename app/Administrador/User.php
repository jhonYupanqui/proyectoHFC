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

    private function tienePermisoEspecial(){
       return $this->role->especial == Role::CON_PERMISOS_TOTAL;
       
    }

    public function HasPermiso($permiso){

        if($this->tienePermisoEspecial()){
           return true;  
        }

        $resultado_permiso = false;

        if (isset($this->role->permisos)) { //verifica si tiene permisos para evaluar

            $resultado_permiso = $this->role->permisos()->where('slug','=',$permiso)->first() != null;

        }
        if(!$resultado_permiso){ //si continua en false
            $resultado_permiso = $this->permisos()->where('slug','=', $permiso)->first() != null; 
        }
         return $resultado_permiso;
      
    }
 
}
