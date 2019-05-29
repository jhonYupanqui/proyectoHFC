@extends('layouts.master')

@section('estilos')
    
@endsection
@section('scripts-header')
    
@endsection

@section('top-left-submenus')
    <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="javascript:void(0)" class="nav-link">Home</a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
        <a href="javascript:void(0)" class="nav-link">Contact</a>
    </li>
@endsection

@section('title-container')
    <h3 class="m-0 text-dark">Dashboard</h3>
@endsection
@section('ruta-navegacion-container')
    @parent
    {{--<li class="breadcrumb-item active">Usuarios</li>--}}
@endsection

@section('aside-right')
    {{-- Aqui el aside del lado derecho, ingresar lo que sedea mostrar--}}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        Bienvenido al Sistema!!
                    </div>
                </div>
        </div>
    </div>
@endsection

@section('scripts-footer')
    
@endsection