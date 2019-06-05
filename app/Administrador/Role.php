<?php

namespace App\Administrador;

use App\Administrador\User;
use App\Administrador\Permiso;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
   use SoftDeletes;

   const SIN_PERMISOS_TOTAL = "NO";
   const CON_PERMISOS_TOTAL = "SI";
   
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

}
