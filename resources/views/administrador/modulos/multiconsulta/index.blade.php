@extends('layouts.master')

@section('titulo_pagina_sistema', 'Multiconsulta')
 
@section('estilos')
    <link rel="stylesheet" href="{{ url('/css/modulos/multiconsulta.css')}}">

@endsection
@section('scripts-header')

@endsection

@section('top-left-submenus')
    @parent
    {{-- Menu Top--}}
@endsection

@section('title-container')
     <h4 class="m-0 text-dark text-uppercase">Multiconsulta</h4> 
    
@endsection
@section('ruta-navegacion-container')
    @parent
     <li class="breadcrumb-item active">Multiconsulta</li>
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    @parent

    @include('administrador.modulos.multiconsulta.partials.searchModal')
   
    <div class="row">
        <section class="col-12 row d-flex justify-content-center" id="multiconsulta_search">
            <div class="col-10 content-form-multi">
              <article id="form_multiconsulta">
                <div class="form-group">
                   <div class="input-group">
                       <div class="input-group-btn">
                           <select name="type_m" id="type_m" class="form-control form-control-sm shadow-sm">
                             {{--<option value="seleccionar">Tipo de busqueda</option>--}}
                             <option value="1">Cod Cliente CMS</option>
                             <option value="2">Mac Address</option>
                             <option value="3">Telefono TFA/CEL</option>
                             <option value="4">Telefono HFC</option>
                           </select>    
                       </div>
                     <input type="text" id="text_m" name="text_m" class="form-control form-control-sm shadow-sm">
                     <span class="input-group-btn">
                       <a href="javascript: void(0)" id="search_m" class="btn btn-sm btn-light shadow-sm">Buscar</a>
                     </span>
                   </div>
                </div>
              </article>
            </div>
       </section>
       <section class="col-12 row d-flex justify-content-center align-items-center flex-wrap flex-column" id="rpta_multiconsulta">
           
       </section>
    </div>

    
@endsection

@section('scripts-footer')  
    <script src="{{ url('/js/sistema/modulos/multiconsulta/index.min.js') }}"></script>
@endsection