<?php

use App\Transformers\RolTransformer;
use App\Transformers\UserTransformer;
use App\Transformers\EmpresaTransformer;

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


    //CIERRE DE SESION
    Route::post('/logout','Modulos\Auth\LoginController@logout')->name('logout');

    //ADMINISTRADOR PRINCIPAL VIEW
    Route::get('/administrador', 'AdministradorController@index')->name('administrador');

    //PASSWORD VIEWS
    Route::get('/password/cambio', 'Modulos\Password\PasswordController@primerCambio')->name('password.change.view');
    Route::post('/password/usuario/{usuario}/update', 'Modulos\Password\PasswordController@update')->name('password.usuario.update');
 
    //PERFIL VIEWS
    Route::get('/administrador/perfil/{usuario}/detalle','Modulos\User\PerfilController@index')->name('modulo.perfil.usuario.index');
 
    //ADMINISTRADOR EMPRESA VIEW
    Route::get('/administrador/empresa','Modulos\Empresa\EmpresaController@index')->name('modulo.empresa.index')
    ->middleware('permiso:modulo.empresa.index');  
    Route::get('/administrador/empresa/{empresa}/detalle','Modulos\Empresa\EmpresaController@show')->name('submodulo.empresa.show')
    ->middleware('permiso:submodulo.empresa.show'); 
    Route::get('/administrador/empresa/{empresa}/editar','Modulos\Empresa\EmpresaController@edit')->name('submodulo.empresa.edit')
    ->middleware('permiso:submodulo.empresa.edit'); 
    Route::get('/administrador/empresa/crear','Modulos\Empresa\EmpresaController@create')->name('submodulo.empresa.store')
    ->middleware('permiso:submodulo.empresa.store'); 

    // ADMINISTRADOR USUARIOS VIEW
    Route::get('/administrador/usuario','Modulos\User\UserController@index')->name('modulo.usuario.index')
    ->middleware('permiso:modulo.usuario.index');
    Route::get('/administrador/usuario/crear','Modulos\User\UserController@create')->name('submodulo.usuario.store')
    ->middleware('permiso:submodulo.usuario.store');
    Route::get('/administrador/usuario/{usuario}/detalle','Modulos\User\UserController@show')->name('submodulo.usuario.show')
    ->middleware('permiso:submodulo.usuario.show');
    Route::get('/administrador/usuario/{usuario}/editar','Modulos\User\UserController@edit')->name('submodulo.usuario.edit')
    ->middleware('permiso:submodulo.usuario.edit');
 
    //ADMINISTRADOR ROLES VIEW
    Route::get('/administrador/rol','Modulos\Rol\RolController@index')->name('modulo.rol.index')
    ->middleware('permiso:modulo.rol.index');
    Route::get('/administrador/rol/crear','Modulos\Rol\RolController@create')->name('submodulo.rol.store')
    ->middleware('permiso:submodulo.rol.store');
    Route::get('/administrador/rol/{rol}/detalle','Modulos\Rol\RolController@show')->name('submodulo.rol.show')
    ->middleware('permiso:submodulo.rol.show');
    Route::get('/administrador/rol/{rol}/editar','Modulos\Rol\RolController@edit')->name('submodulo.rol.edit')
    ->middleware('permiso:submodulo.rol.edit');
  

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
    Route::get('/administrador/usuarios/lista', 'Modulos\User\UserController@lista')->name('submodulo.usuario.list.ajax')
    ->middleware('permiso:modulo.usuario.index');
    Route::post('/administrador/empresa/{empresa}/rol/{rol}/usuario/store', 'Modulos\User\UserController@store')->name('submodulo.usuario.store.ajax')
    ->middleware('permiso:submodulo.usuario.store')
    ->middleware('transform.input:'. UserTransformer::class);
    Route::put('/administrador/empresa/{empresa}/rol/{rol}/usuario/{usuario}/update', 'Modulos\User\UserController@update')->name('submodulo.empresa.edit.ajax')
    ->middleware('permiso:submodulo.empresa.edit')
    ->middleware('transform.input:'. UserTransformer::class);
    Route::post('/administrador/usuario/{usuario}/eliminar', 'Modulos\User\UserController@delete')->name('submodulo.usuario.delete.ajax')
    ->middleware('permiso:submodulo.usuario.delete');

    //ADMINISTRADOR ROLES
    Route::get('/administrador/roles/lista', 'Modulos\Rol\RolController@lista')->name('modulo.rol.index.ajax')
    ->middleware('permiso:modulo.rol.index');
    Route::post('/administrador/rol/store', 'Modulos\Rol\RolController@store')->name('submodulo.rol.store.ajax')
    ->middleware('permiso:submodulo.rol.store')
    ->middleware('transform.input:'. RolTransformer::class);
    Route::put('/administrador/rol/{rol}/update', 'Modulos\Rol\RolController@update')->name('submodulo.rol.edit.ajax')
    ->middleware('permiso:submodulo.rol.edit')
    ->middleware('transform.input:'. RolTransformer::class);
    Route::post('/administrador/rol/{rol}/eliminar', 'Modulos\Rol\RolController@delete')->name('submodulo.rol.delete.ajax')
    ->middleware('permiso:submodulo.rol.delete'); 

    //ROLES - PERMISOS
    Route::get('/administrador/roles/{rol}/permisos', 'Modulos\Rol\RolController@permisos')->name('submodulo.rol.permisos.lista');
     

    //ADMINISTRADOR EMPRESA
    Route::get('/administrador/empresas/lista','Modulos\Empresa\EmpresaController@lista')->name('modulo.empresa.index.ajax')
    ->middleware('permiso:modulo.empresa.index'); 
    Route::post('/administrador/empresa/store','Modulos\Empresa\EmpresaController@store')->name('submodulo.empresa.store.ajax')
    ->middleware('permiso:submodulo.empresa.store')
    ->middleware('transform.input:'. EmpresaTransformer::class);
    Route::put('/administrador/empresa/{empresa}/update','Modulos\Empresa\EmpresaController@update')->name('submodulo.empresa.edit.ajax')
    ->middleware('permiso:submodulo.empresa.edit')
    ->middleware('transform.input:'. EmpresaTransformer::class);
    Route::post('/administrador/empresa/{empresa}/eliminar','Modulos\Empresa\EmpresaController@delete')->name('submodulo.empresa.delete.ajax')
    ->middleware('permiso:submodulo.empresa.delete');
      
});
