<?php

namespace App\Administrador;

use App\Administrador\Role;
use App\Administrador\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permiso extends Model
{
    use SoftDeletes;

    const TIPO_MODULO = "Modulo";
    const TIPO_SUBMODULO = "Submodulo";

    protected $table = 'permisos';

    protected $fillable = [
        'nombre',
        'slug',
        'imagen',
        'tipo',
        'referencia',
        'descripcion',
    ];

   public function roles(){
       return $this->belongsToMany(Role::class);
   }

   public function users(){
       return $this->belongsToMany(User::class);
   }

}
