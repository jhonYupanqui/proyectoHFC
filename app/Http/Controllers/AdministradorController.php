<?php

namespace App\Http\Controllers;

use App\Administrador\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GeneralController;
use UserFunctions;

class AdministradorController extends GeneralController
{
    public function index()
    {  
        $usernameAuth =  Auth::user()->username;
        $userFunctions = new UserFunctions;
        $cantidadPasswordLog = $userFunctions->cantidadHistorialPasswordByIdUser($usernameAuth);
        
        if((int)$cantidadPasswordLog == 0){
            return redirect()->route('password.change.view');
        }

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
