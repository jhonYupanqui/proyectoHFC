<?php

namespace App\Http\Controllers\Modulos\User;

use UserFunctions;
use App\Administrador\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\PerfilRequest;
use Illuminate\Database\QueryException;
use App\Http\Controllers\GeneralController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PerfilController extends GeneralController
{
    public function detalle($username, Request $request)
    {  
        
        $usuario = User::where('username',$username)->first();
        if (empty($usuario)) {
            throw new NotFoundHttpException();//404
        }
        $usuariofunctions = new UserFunctions;
       
        $ultimoAcceso = $usuariofunctions->ultimoAccesoUser($usuario->username,", 1");
        $fechaUltimoAcceso = "";

        if (count($ultimoAcceso) > 0) {
            $fechaUltimoAcceso = $ultimoAcceso[0]->fecha;
        }
 
        return view('administrador.perfil.usuario.detalle',[
            "usuario"=>$this->showModJsonOne($usuario),
            "ultimoAcceso"=>$fechaUltimoAcceso
        ]);
    }

    public function updatePerfil(User $usuario,PerfilRequest $request)
    {
        
        try {
            DB::beginTransaction();
              #INICIO UPDATE
                    
                $usuario->dni = $request->dni;
                $usuario->telefono = $request->telefono;
                $usuario->email = $request->email;
                  
                $usuario->save();
      
              #END UPDATE
            DB::commit();
          } catch(QueryException $ex){ 
             //dd($ex->getMessage()); 
              DB::rollback();
              return $this->errorResponse(["Hubo un problema en la actualización, intente nuevamente!."],402);
          }catch(\Exception $e){
              //dd($e->getMessage()); 
              DB::rollback();
              return $this->errorResponse(["Hubo un error inesperado!, intente nuevamente!."],402);
          } 
      
            return $this->showModJsonOne($usuario);

    }

    public function updatePassword(User $usuario,PerfilRequest $request)
    {

        $userFunctions = new UserFunctions;
        $userFunctions->esValidoNuevoPassword($usuario,$request->password);//valida password
 
        try {
            DB::beginTransaction();
              #INICIO UPDATE

                $userFunctions->registrarPasswordOld($request->password,$usuario->username);
                    
                $usuario->password = bcrypt($request->password);
                  
                $usuario->save();

                 //limpia ultimos registros password manteniendo solo los ultimos 8
                 $cantidad_historial = $userFunctions->cantidadHistorialPasswordByIdUser($usuario->username);
                 if ($cantidad_historial > 8) { //si excede los 8 registros
                     $limit = $cantidad_historial - 8; //conservamos los 8 ultimos
                     $userFunctions->eliminarUltimoPasswordHistorial($usuario->username,$limit);
                 }
      
              #END UPDATE
            DB::commit();
        } catch(QueryException $ex){ 
            dd($ex->getMessage()); 
            DB::rollback();
            return $this->errorResponse(["Hubo un problema en la actualización, intente nuevamente!."],402);
        }catch(\Exception $e){
            dd($e->getMessage()); 
            DB::rollback();
            return $this->errorResponse(["Hubo un error inesperado!, intente nuevamente!."],402);
        } 

        return $this->showModJsonOne($usuario);

    }

}
