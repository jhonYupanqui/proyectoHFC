<?php

namespace App\Http\Controllers\Modulos\User;

use App\Administrador\User;
use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;

class UserController extends GeneralController
{
    public function index()
    {
        return view('administrador.modulos.user.index');
    }

    public function list(Request $request)
    { 
       $usuarios = User::all();
      //return $this->showContJsonAll($usuarios,true,true,true,true);
       $usuario = new User;
       return $this->showModJsonAll($usuario,true,false,true,false,true,true);
    }
}
