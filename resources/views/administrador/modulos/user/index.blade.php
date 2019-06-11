@extends('layouts.master')

@section('titulo_pagina_sistema', 'Usuarios')

@section('estilos')
    
@endsection
@section('scripts-header')
    
@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Usuarios</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Usuarios</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent
  
    <div class="row">
        <div class="col-12">
                <div class="card">
                    <div class="card-header px-2 py-1">
                        <a href="{{route('administrador')}}" class="btn btn-sm btn-outline-success mx-1"><i class="fa fa-arrow-left"></i> Atras</a>
                        @if(Auth::user()->HasPermiso('submodulo.usuario.store'))
                            <a href="{{route('submodulo.usuario.store')}}" class="btn btn-sm btn-outline-primary shadow-sm mx-1" id="activeModalUserStore">Crear<i class="fa fa-user-plus" aria-hidden="true"></i></a>
                        @endif
                    </div>

                    <div class="card-body px-2 py-1"> 
                        <div class="col-md-12 p-0 mb-2 mt-2">
                            <div class="input-group">  
                                    <input type="text" id="nombre" name="nombre" class="form-control form-control-sm mx-1 shadow-sm" placeholder="nombre">
                                    <input type="text" id="apellidos" name="apellidos" class="form-control form-control-sm mx-1 shadow-sm" placeholder="apellidos">
                                    <input type="text" id="documento" name="documento" class="form-control form-control-sm mx-1 shadow-sm" placeholder="DNI">
                                    <input type="text" id="usuario" name="usuario" class="form-control form-control-sm mx-1 shadow-sm" placeholder="usuario">
                                <span class="input-group-btn">
                                <a href="javascript: void(0)" id="searchData" class="btn btn-outline-success btn-sm shadow-sm" ><i class="fa fa-search"></i></a>
                                </span>
                            </div>
                        </div>

                        <div class="content info-table mx-1" id="infor-general-result">
                            <div class="row m-0"> 
                                <div class="col-md-4 d-flex align-items-center justify-content-center" id="details-result-data">
                                    
                                </div>
                                <div class="col-md-4 d-flex align-items-center justify-content-center" id="export-result-data">
                                    
                                </div>
                                <div class="col-md-4" id="paginacion-result-data"> 
                    
                                </div>
                            </div>
                        </div>

                        <div class="content-list-general-result p-1">
                            <div class="table-responsive tableFixHead">
                                <table class="table table-bordered w-100 m-auto table-personalizate" id="table_list_general_index">
                                    <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Nombre <i class="fa fa-sort-alpha-asc icon-orde_by" data-order="nombre"></i></th>
                                        <th>Apellidos <i class="fa fa-sort-alpha-asc icon-orde_by" data-order="apellidos"></i></th>
                                        <th>Usuario <i class="fa fa-sort-alpha-asc icon-orde_by" data-order="usuario"></i></th>
                                        <th>DNI <i class="fa fa-sort-numeric-asc icon-orde_by" data-order="usuario"></i></th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody id="result_iteraction_list">
                                        <tr>
                                        <td colspan="6" class="text-center">
                                            <div id="carga_person">
                                            <div class="loader">
                                                
                                            </div>
                                            </div>
                                        </td>
                                        </tr>    
                                    </tbody>
                                </table>
                            </div>
                                <div id="result_page_list" class="my-3">
                                
                                </div>
                    
                        </div>
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('scripts-footer')  
    <script src="{{ asset('js/sistema/modulos/users/index.min.js') }}"></script>
@endsection