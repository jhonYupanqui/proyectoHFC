<?php

namespace App\Http\Controllers\Modulos\Rol;

use App\Administrador\Role;
use Illuminate\Http\Request;
use App\Administrador\Permiso;
use App\Http\Controllers\GeneralController;

class RolController extends GeneralController
{
    public function permisos(Role $rol,Request $request){
        if ($request->ajax()) {
      
            if($rol->esRolEspecial()){
                $permisos = Permiso::all();
                return $this->showContJsonAll($permisos);
            }

            $permisos = $rol->permisos;
            //dd($permisos);
            return $this->showContJsonAll($permisos);
        }
        return abort(404);
    }

}
