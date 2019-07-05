<?php

namespace App\Http\Controllers\Modulos\Multiconsulta;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Functions\MulticonsultaFunctions;
use App\Http\Controllers\GeneralController;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MulticonsultaController extends GeneralController
{
   public function index()
   {
        return view('administrador.modulos.multiconsulta.index');
   }

   public function cantidadServicios(Request $request)
   { 
        //obtener la cantidad de resultados de la consulta 
        $usuarioAuth = Auth::user();
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
        $fech_hor = date("Y-m-d H:i:s"); 

        $tipoBus = $request->type_data;
        $bus = $request->text;
  
        $multiconsultaFuntions = new MulticonsultaFunctions;
        $multiconsultaFuntions->validarSearch($bus,$tipoBus,$fech_hor,$rolNombre);//verifica que la consulta sea valida
        
        $armado_query = $multiconsultaFuntions->ArmandoQuery($tipoBus,$bus);
         
        //throw new HttpException(422,"dispara un error");

        $recordP= $multiconsultaFuntions->queryPrincipal($armado_query["filtroWhere"],$armado_query["limit"]);
 

        if (count($recordP) > 1) {
            $recordP = $multiconsultaFuntions->resBusVarios($bus);
            return $this->resultData(array(
                 "cantidad"=>count($recordP),
                 "resultado"=>json_encode($recordP)
            )); 
        }

        if (count($recordP) == 0) {
          return $this->resultData(array(
               "cantidad"=>0,
               "resultado"=>[]
          )); 
        }

        //Aqui se debe procesar el multiconsulta result con blade y functions
        //solo es un resultado
 
        $newResultMulti = $multiconsultaFuntions->procesarMulticonsulta($recordP);

        return $this->resultData(
             array(
               'cantidad' => 1,
               'resultado' => view('administrador.modulos.multiconsulta.searchResult',["resultadoMulti"=>$newResultMulti])->render(),
             )
        ); 
         
   }

   public function searchByMacAddress(Request $request)
   { 
        //rol del usuario - pero se utiliza como area
        $usuarioAuth = Auth::user();
        $idusuario =  $usuarioAuth->id;
        $rolNombre = $usuarioAuth->role->nombre;
        $usuario = $usuarioAuth->username;
 
        $fech_hor = date("Y-m-d H:i:s");
   
        $tipoBus = $request->type_data;
        $bus = $request->text;
         
        $multiconsultaFuntions = new MulticonsultaFunctions;
        $multiconsultaFuntions->validarSearchMacAddress($bus,$tipoBus,$fech_hor,$rolNombre);//verifica que la consulta sea valida
         
        $armado_query = $multiconsultaFuntions->ArmandoQuery($tipoBus,$bus);

        $recordP= $multiconsultaFuntions->queryPrincipal($armado_query["filtroWhere"],$armado_query["limit"]);
 
        $newResultMulti = $multiconsultaFuntions->procesarMulticonsulta($recordP);
  
        return $this->resultData(
               array(
                    'cantidad' => 1,
                    'resultado' => view('administrador.modulos.multiconsulta.searchResult',["resultadoMulti"=>$newResultMulti])->render(),
               )
          ); 
        
    }

}
