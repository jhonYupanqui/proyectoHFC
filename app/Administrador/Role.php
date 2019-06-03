<?php

namespace App\Administrador;

use App\Administrador\User;
use App\Administrador\Permiso;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
   use SoftDeletes;

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
