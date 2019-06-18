<?php

namespace App\Http\Controllers\Modulos\Auth;

use UserFunctions;
use App\Administrador\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index(){
        return view('auth.login');
    }

    public function login(Request $request){
 
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
         
        $credentials = $this->validate($request,$rules);


        $userFunctions = new UserFunctions;

        #CANTIDAD DE INTENTOS VALIDACION
            $cantidad_max_intentos = 5;
            $intentos_max_minutos = 30;
            $cantidad_intentos = $userFunctions->getCantidadUltimosIntentosPorTiempo($request->username,$intentos_max_minutos);
             
            if($cantidad_intentos >= 5){
                return back()
                ->withErrors(['auth'=>"Superaste el numero de intentos para acceder al sistema, intenta dentro de $intentos_max_minutos minutos nuevamente!."])
                ->withInput(request(['username']));
            }
        #FIN CANTIDAD INTENTOS

         
        #VALIDANDO CREDENCIALES DE LOGIN
            if(Auth::attempt($credentials)){ //valida credenciales usuario y password
                $userAuth = Auth::user();
                if ($userAuth->estado == User::ESTADO_INACTIVO) { //usuario inactivo
                    Auth::logout();
                    $userFunctions->registraIntentosUserLogin($request->username,"NO");//registra intento fallido
                    
                    return back()
                    ->withErrors(['auth'=>"El estado de su cuenta no está activa, verifique con el administrador!."])
                    ->withInput(request(['username']));
                }
                //si su estado está activo
                $userFunctions->limpiaIntentosUserLogin($request->username); //limpia los accessos errados
                $userFunctions->registraIntentosUserLogin($request->username,"SI");//registra acceso correcto
                return redirect()->route('administrador');
    
            }else{
                $userFunctions->registraIntentosUserLogin($request->username,"NO");//registra intento fallido
    
                $intentos_maximos_disponibles = $cantidad_max_intentos - ($cantidad_intentos +1);
                $texto_plural_singular = $intentos_maximos_disponibles > 1 ? "intentos" : "intento";
                
                    return back()
                    ->withErrors(['auth'=>"Las credenciales son incorrectas, intente nuevamente, recuerde solo tiene $intentos_maximos_disponibles $texto_plural_singular !."])
                    ->withInput(request(['username']));
                 
            }
        #FIN VALIDACION CREDENCIALES 
  
        /*return back()
                ->withErrors(['auth'=>trans('auth.failed'),"username"=>"problemas con el usuario"])
                ->withInput(request(['username','auth']));*/
        
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
