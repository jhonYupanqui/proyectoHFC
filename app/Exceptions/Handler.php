<?php

namespace App\Exceptions;

use Exception;
use App\Traits\SystemResponser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    use SystemResponser;//usamos nuestro apiresponser traits credo antes
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        //empezamos a validar para cada caso
        if($exception instanceof ValidationException){ //indicamos que vamos ha usar nuestra funcion  convertValidationExceptionToResponse
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if($exception instanceof AuthenticationException){ //Validamos el error ha mostrar cuando no este logueado el usuario
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof ModelNotFoundException){ //si hubiera un error de un modelo no encontrado jemplo: /users/1345498754 :get
            $modelo = strtolower(class_basename($exception->getModel()));//acceediendo al modelo que sale error
            return $this->ErrorsExceptionGeneral($request,"No existe ninguna instancia de {$modelo}", 404);
        }

        if($exception instanceof AuthorizationException){ // Validar errores cuando no tiene permisos el usuario
            return $this->ErrorsExceptionGeneral($request,"No posee permisos para ejecutar esta acción", 403);
            //estado 403 es el de no autorizado
        }

        if($exception instanceof NotFoundHttpException){//valida una ruta que no existe
            return $this->ErrorsExceptionGeneral($request,"No se encontro el recurso solicitado: ".$request->path(), 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException){//la ruta es correcta pero el method usado no es el correcto
            return $this->ErrorsExceptionGeneral($request,"El método ".$request->method()." especificado en la petición no es válido",405);
        }

        if($exception instanceof HttpException){//validando error de http cualquiera que tenga que ver con http
            return $this->ErrorsExceptionGeneral($request,$exception->getMessage(),$exception->getStatusCode());
        }

        if($exception instanceof QueryException){//valida errores de eliminacion por id por estar relacionado
            $codigo = $exception->errorInfo[1];
            if($codigo == 1451){
                return $this->ErrorsExceptionGeneral($request,"No se puede eliinar de forma permanente el recurso porque está relacionado con algún otro.",409);
                //codigo 409 es de conflicto
            }
        }

        if(config('app.debug')){
            return parent::render($request, $exception);
        }

        return $this->ErrorsExceptionGeneral($request,'Falla inesperada. Intente luego',500);
         
    }

    //Function creados personalizados para generalizar respuestas:

    protected function unauthenticated($request, AuthenticationException $exception)
    {//validamos que el usuario esta autenticado, caso contrario retorna error
       
        if($this->isFrontend($request)){
           return redirect()->route('modulo.login.index');
        }
        return $this->errorResponse('No autenticado.', 401); 
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
 
        $errors = $e->validator->errors()->getMessages();

       if($this->isFrontend($request)){
            return $request->ajax() ? $this->errorResponse($errors, 422) : redirect()
                    ->back()
                    ->withInput($request->input())
                    ->withErrors($errors);
        }

        return $this->errorResponse($errors, 422);
        
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }

    private function ErrorsExceptionGeneral($request, $mensaje, $codigo)
    {
        if($this->isFrontend($request)){

            if($request->ajax()){
                return $this->errorResponse($mensaje, $codigo);
            }

            switch ($codigo) {
                case 403:
                    abort(403, 'Página no autorizada.');
                    break; 
                case 404:
                    abort(404, 'Página no encontrada.');
                    break;
                case 405:
                    abort(405, 'Error en el servicio.');
                    break;
                case 409:
                    abort(409, 'Conflicto con la BD.');
                    break;
                default:
                    abort($codigo, 'Error en el servicio.');
                    break;
            } 
         }

        return $this->errorResponse($mensaje, $codigo);
    }
    
}
