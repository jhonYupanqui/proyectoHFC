<?php

namespace App\Http\Controllers;

use App\Administrador\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GeneralController;

class AdministradorController extends GeneralController
{
    public function index()
    {
        return view('administrador.index');
    }

    public function list(Request $request)
    {
        if($request->ajax()){
            $user = Auth::user();
            $resultado_modulos = User::getModulosByUserAuth($user);
                
            return $this->showContJsonAll($resultado_modulos,true);
        }
        return abort(404);
       
    }
}
