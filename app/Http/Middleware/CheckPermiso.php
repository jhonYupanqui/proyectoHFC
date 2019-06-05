<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermiso
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

        if ($this->isFrontend($request)) {
            echo "si es front end"; 
        }
        dd("no es front end");
        if(! $request->user()->HasPermiso($permiso)){
             return redirect('errors.403');
            // return response()->json(['error' => 'Unauthorised'], 401);
            //return $this->errorResponse('No posee permisos para ejecutar esta acción', 403);
        } 

        return $next($request);
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

}
