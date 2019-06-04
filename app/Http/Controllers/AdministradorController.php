<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\GeneralController;

class AdministradorController extends GeneralController
{
    public function index()
    {
        return view('administrador.index');
    }

    public function list()
    {
        //retornar lsta de modulos segun usuario
    }
}
