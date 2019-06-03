<?php

namespace App\Http\Controllers\Modulos\Empresa;

use Illuminate\Http\Request;
use App\Administrador\Empresa;
use App\Http\Requests\EmpresaRequest;
use App\Http\Controllers\GeneralController;

class EmpresaController extends GeneralController
{
    public function index(){
        $empresas = Empresa::all();
         
        return $this->showContJsonAll($empresas);
    }

    public function lista(){
        $empresas = Empresa::all();
         
        return $this->showContJsonAll($empresas);
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
