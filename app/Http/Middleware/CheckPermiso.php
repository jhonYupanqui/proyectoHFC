<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\GeneralController;

class CheckPermiso extends GeneralController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permiso)
    {
        $permiso_usuario = $request->user()->HasPermiso($permiso);

        if ($this->isFrontend($request)) { //verifica si la peticion es por web 
            if(!$permiso_usuario && $request->ajax()){ // Si el permiso es falso y si la peticion es ajax de la web
                return $this->errorMessage("Unauthorised",403);
            }
             
            //Si es web pero no es ajax, es en el navegador
            if(!$permiso_usuario){// Si el permiso es falso . 
                abort(403, 'Unauthorized action.');
            }

           //Si el permiso es verdadero y si la peticion es ajax de la web
            if($permiso_usuario){
                $next($request);
            }
              
           // return ($permiso_usuario && $request->ajax())? $next($request) : abort(404, 'Página no encontrada');
           ///$next($request);
            //Si la peticion es correcta con su permisos,
            //no se mostrará el resultado, ya que no es peticion web
            //mostrará error 404 porque no existe a nivel web eta peitcion solo ajax
           
        }
       
        //La peticion no es por web - sino es en API
        return (!$permiso_usuario) ? $this->errorMessage("Unauthorised",403) : $next($request);
         
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

}
