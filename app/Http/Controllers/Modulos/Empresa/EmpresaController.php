<?php

namespace App\Http\Controllers\Modulos\Empresa;

use Illuminate\Http\Request;
use App\Administrador\Empresa;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\EmpresaRequest;
use App\Http\Controllers\GeneralController;

class EmpresaController extends GeneralController
{
    public function index(){
        return view('administrador.modulos.empresa.index');
    }

    public function lista(Request $request){
        if($request->ajax()){

            $usuarioAuth = Auth::user();
            
            #Filtrando lista de Empresas
              $empresa = Empresa::all();
             
              $dataListReturn = datatables()
                                ->collection($empresa);
            
                if( $usuarioAuth->HasPermiso('submodulo.empresa.show') || 
                    $usuarioAuth->HasPermiso('submodulo.empresa.edit')  ||
                    $usuarioAuth->HasPermiso('submodulo.empresa.delete')
                  ){
                    
                    $dataListReturn = $dataListReturn
                                      ->only(['id','nombre','btn'])
                                      ->addColumn('btn', 'administrador.modulos.empresa.partials.acciones')
                                      ->rawColumns(['btn'])
                                      ->toJson();
                    
                  }else{
                    $dataListReturn = $dataListReturn
                                      ->only(['id','nombre'])
                                      ->toJson();
                  }  
                    
                  return $dataListReturn;
               
            #End Filtro
             
         }
        return abort(404); 
       
    }

    public function show(Empresa $empresa){
        return $this->showModJsonOne($empresa);
    }

    public function edit(Empresa $empresa){
        return $this->showModJsonOne($empresa);
    }

    public function store(EmpresaRequest $request){

    }

    public function update(Empresa $empresa,EmpresaRequest $request){

    }

    public function delete(Empresa $empresa){

    }
}
