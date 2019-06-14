<?php

namespace App\Administrador;

use App\Administrador\User;
use App\Administrador\Permiso;
use App\Transformers\RolTransformer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
   use SoftDeletes;


   const SIN_PERMISOS_TOTAL = "NO";
   const CON_PERMISOS_TOTAL = "SI";
   
   public $transformer = RolTransformer::class;

   protected $table = 'roles';


    protected $fillable = [
        'nombre'
    ];

    public function permisos(){
        return $this->belongsToMany(Permiso::class);
    }

    public function users(){
        return $this->hasMany(User::class);
    }

    public static function getSubRolesByRol()
    {
        $rol_asignado = Auth::user()->role_id;
         
        if(Auth::user()->tienePermisoEspecial()){
            return Role::all();
        }
        $roles_hijos = Role::where('referencia','=',$rol_asignado)->get();
        return $roles_hijos;
    }

}
