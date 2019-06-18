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
 

//->middleware('transform.input:' . UserTransformer::class);
 
Route::group(['middleware' => 'auth'], function () {

    //ADMINISTRADOR PRINCIPAL VIEW
    Route::get('/administrador', 'AdministradorController@index')->name('administrador');
    Route::post('/logout','Modulos\Auth\LoginController@logout')->name('logout');
    


    //ADMINISTRADOR EMPRESA VIEW
    Route::get('/administrador/empresas','Modulos\Empresa\EmpresaController@index')->name('modulo.empresa.index')
    ->middleware('permiso:modulo.empresa.index'); 

    // ADMINISTRADOR USUARIOS VIEW
    Route::get('/administrador/usuario','Modulos\User\UserController@index')->name('modulo.usuario.index')
    ->middleware('permiso:modulo.usuario.index');
    Route::get('/administrador/usuarios/crear','Modulos\User\UserController@create')->name('submodulo.usuario.store')
    ->middleware('permiso:submodulo.usuario.store');
    Route::get('/administrador/usuario/{usuario}/detalle','Modulos\User\UserController@show')->name('submodulo.usuario.show')
    ->middleware('permiso:submodulo.usuario.show');
    Route::get('/administrador/usuario/{usuario}/editar','Modulos\User\UserController@edit')->name('submodulo.usuario.edit')
    ->middleware('permiso:submodulo.usuario.edit');

    //ADMINISTRADOR ROLES VIEW
    Route::get('/administrador/roles/{rol}/permisos', 'Modulos\Rol\RolController@permisos')->name('submodulo.rol.permisos.lista');
    Route::get('/administrador/roles','Modulos\Rol\RolController@index')->name('modulo.rol.index')
    ->middleware('permiso:modulo.rol.index');

    //ADMINISTRADOR MULTICONSULTA VIEW
    Route::get('/administrador/multiconsulta','Modulos\Multiconsulta\MulticonsultaController@index')->name('modulo.multiconsulta.index')
    ->middleware('permiso:modulo.multiconsulta.index');

    //ADMINISTRADOR ALBOL DE DECISIONES VIEW
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
    Route::get('/administrador/usuarios/lista', 'Modulos\User\UserController@list')->name('submodulo.usuario.list')
    ->middleware('permiso:modulo.usuario.index');
    Route::get('/administrador/usuarios/lista-ajax', 'Modulos\User\UserController@listAjax')->name('submodulo.usuario.list-ajax')
    ->middleware('permiso:modulo.usuario.index');
    Route::post('/administrador/empresa/{empresa}/rol/{rol}/usuario/store', 'Modulos\User\UserController@store')->name('submodulo.usuario.store.ajax')
    ->middleware('permiso:submodulo.usuario.store')
    ->middleware('transform.input:'. UserTransformer::class);
    Route::put('/administrador/empresa/{empresa}/rol/{rol}/usuario/{usuario}/update', 'Modulos\User\UserController@update')->name('submodulo.empresa.edit.ajax')
    ->middleware('permiso:submodulo.empresa.edit')
    ->middleware('transform.input:'. UserTransformer::class);

    //ADMINISTRADOR EMPRESA
    Route::get('/administrador/empresa/lista','Modulos\Empresa\EmpresaController@list')->name('submodulo.empresa.list')
    ->middleware('permiso:modulo.empresa.index'); 
      
});
