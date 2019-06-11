<?php

use App\Transformers\UserTransformer;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
 


 
Route::get('/','Modulos\Auth\LoginController@index')->name('modulo.login.index')->middleware('guest');
Route::post('/login','Modulos\Auth\LoginController@login')->name('login');
Route::post('/logout','Modulos\Auth\LoginController@logout')->name('logout');
//->middleware('transform.input:' . UserTransformer::class);
 
Route::group(['middleware' => 'auth'], function () {

    //ADMINISTRADOR PRINCIPAL VIEW
    Route::get('/administrador', 'AdministradorController@index')->name('administrador');

    //EMPRESA VIEW
    Route::get('/administrador/empresas','Modulos\Empresa\EmpresaController@index')->name('modulo.empresa.index')
    ->middleware('permiso:modulo.empresa.index'); 

    //USUARIOS VIEW
    Route::get('/administrador/usuarios','Modulos\User\UserController@index')->name('modulo.usuario.index')
    ->middleware('permiso:modulo.usuario.index');
    Route::get('/administrador/usuarios/create','Modulos\User\UserController@create')->name('submodulo.usuario.store')
    ->middleware('permiso:submodulo.usuario.store');
    Route::get('/administrador/usuarios/{usuario}/show','Modulos\User\UserController@show')->name('submodulo.usuario.show')
    ->middleware('permiso:submodulo.usuario.show');
    Route::get('/administrador/usuarios/{usuario}/edit','Modulos\User\UserController@edit')->name('submodulo.usuario.edit')
    ->middleware('permiso:submodulo.usuario.edit');

    //MODULOS VIEW
    Route::get('/administrador/roles','Modulos\Rol\RolController@index')->name('modulo.rol.index')
    ->middleware('permiso:modulo.rol.index');

    //MULTICONSULTA VIEW
    Route::get('/administrador/multiconsulta','Modulos\Multiconsulta\MulticonsultaController@index')->name('modulo.multiconsulta.index')
    ->middleware('permiso:modulo.multiconsulta.index');

    //ALBOL DE DECISIONES VIEW
    Route::get('/administrador/arbol-decisiones','Modulos\Arbol\ArbolController@index')->name('modulo.arbol-decision.index')
    ->middleware('permiso:modulo.arbol-decision.index');

    //LLamadas VIEW
    Route::get('/administrador/llamadas','Modulos\Llamada\LlamadaController@index')->name('modulo.llamadas.index')
    ->middleware('permiso:modulo.llamadas.index');

    // --------------      -------------- //
    // -------------- JSON -------------- //
    // --------------      -------------- //

    //ADMINISTRADOR PRINCIPAL
    Route::get('/administrador/lista', 'AdministradorController@list')->name('administrador.list');
     
    //ADMINISTRADOR USUARIOS
    Route::post('/administrador/usuarios/lista', 'Modulos\User\UserController@list')->name('submodulo.usuario.list')
    ->middleware('permiso:submodulo.usuario.list');
     

});
