<?php

namespace App\Http\Controllers\Modulos\Auth;

use Carbon\Carbon;
use UserFunctions;
use App\Administrador\User;
use Illuminate\Http\Request;
use App\Administrador\Parametro;
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
            //$cantidad_max_intentos = 5;
            //$intentos_max_minutos = 30;
            //$cantidad_max_intentos = Parametro::find(3)->period;
            $cantidad_max_intentos = Parametro::getIntentosMaximosLogin();
            $intentos_max_minutos = Parametro::getMinutosReactivacionLogin();

            //$queryUltimosMinutos = $intentos_max_minutos." MINUTE";
            $ultimosIntentos = $userFunctions->getUltimosIntentosPorTiempo($request->username,"NO",$intentos_max_minutos,"MINUTE");
            
            $cantidad_intentos = count($ultimosIntentos);

            if($cantidad_intentos >= $cantidad_max_intentos){
 
                //traer el tiempo que queda restante
                $quedan_minutos_reintentar = $intentos_max_minutos;

                if ($cantidad_intentos > 0) {
                    
                   $fechaBDIntentosUltimo = Carbon::create($ultimosIntentos[0]->fecha);
                   $ultimo_acceso_fallido_minutos_pasados = $fechaBDIntentosUltimo->diffInMinutes(Carbon::now());
                   $quedan_minutos_reintentar = (int) $intentos_max_minutos - $ultimo_acceso_fallido_minutos_pasados;
                }
                   
                return back()
                ->withErrors(['auth'=>"Superaste el numero de intentos para acceder al sistema, intenta dentro de $quedan_minutos_reintentar minutos nuevamente!."])
                ->withInput(request(['username']));
            }
        #FIN CANTIDAD INTENTOS

        config(['session.lifetime' => 5]);
        #VALIDANDO CREDENCIALES DE LOGIN
            if(Auth::attempt($credentials)){ //valida credenciales usuario y password
                $userAuth = Auth::user();
                $usuarioUpdate = User::find($userAuth->id);

                //Valido si usuario está activo
                if ($userAuth->estado == User::ESTADO_INACTIVO) { //usuario inactivo
                    Auth::logout();
                    $userFunctions->registraIntentosUserLogin($request->username,"NO");//registra intento fallido
                    
                    return back()
                    ->withErrors(['auth'=>"El estado de su cuenta no está activa, verifique con el administrador!."])
                    ->withInput(request(['username']));
                }

                //Valido que no haya cambiado su password en el tiempo determinado caso contrario bloquear
                $dias_ultimo_cambio_pass = $userFunctions->getCantidadDiasUltimoCambioPassword($request->username);
                
                if (count($dias_ultimo_cambio_pass) > 0) { // si tiene un último cambio 

                    if ((int)$dias_ultimo_cambio_pass[0]->diascambio > Parametro::getDiasCambioPassword()) { //supero los numeros de dias en cambio de password
                        //se desactiva su cuenta 
                        $userFunctions->inactivarUsuario($usuarioUpdate);
                         
                        Auth::logout();
    
                        return back()
                        ->withErrors(['auth'=>$request->username." no actualizaste tu contraseña hace mas de ".Parametro::getDiasCambioPassword()." días. Tu cuenta está desactivada."])
                        ->withInput(request(['username'])); 
                    }
                }
                

                //Valido si su suenta estaba inactiva mucho tiempo segun la configuracion del sistema 
                $ultimoLoginCorrecto = $userFunctions->ultimoAccesoUser($request->username);
                
                if (count($ultimoLoginCorrecto) > 0) { // ya tiene inicios de sessiones antiguas
                     //validar cuanto tiempo de inactividad tiene
                    $fecha_ultima_session = Carbon::create($ultimoLoginCorrecto[0]->fecha);
                    
                    $diferencia_dias_ultimo_login = $fecha_ultima_session->diffInDays(Carbon::now());
                    
                    $maximos_dias_inactividad_sesion = Parametro::getDiasInactividadCuenta();

                    if ($diferencia_dias_ultimo_login > $maximos_dias_inactividad_sesion) {
                        $userFunctions->inactivarUsuario($usuarioUpdate);
                        Auth::logout();

                        return back()
                        ->withErrors(['auth'=>"No inició sessión en más de $maximos_dias_inactividad_sesion días. Su cuenta está desactivada."])
                        ->withInput(request(['username'])); 
                    }
                      
                }
                //si su estado está activo, cambio password con tiempo y su ultima sesion no paso los dias de inactividad
                $userFunctions->limpiaIntentosUserLogin($request->username); //limpia los accessos errados
                $userFunctions->registraIntentosUserLogin($request->username,"SI");//registra acceso correcto
 
                 
                    //config(['session.lifetime' => 1]);  
                   // config('session.lifetime', 1);
                    
                   // $tiempo_session =  config('session.lifetime');
                   // dd($tiempo_session);
                    return redirect()->route('administrador');
                
    
            }else{
                $userFunctions->registraIntentosUserLogin($request->username,"NO");//registra intento fallido
    
                $intentos_maximos_disponibles = $cantidad_max_intentos - ($cantidad_intentos + 1);
                $texto_plural_singular = $intentos_maximos_disponibles > 1 ? "intentos" : "intento";
                
                if ($intentos_maximos_disponibles == 0) {
                    return back()
                    ->withErrors(['auth'=>"Superaste el numero de intentos para acceder al sistema, intenta dentro de $intentos_max_minutos minutos nuevamente!."])
                    ->withInput(request(['username']));
                }
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
