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
    Route::get('/perfil/{username}/detalle','Modulos\User\PerfilController@detalle')->name('perfil.usuario.detalle');
     
    //SEGURIDAD VIEW
    Route::get('/administrador/seguridad','Modulos\Seguridad\SeguridadController@index')->name('modulo.seguridad.index')
    ->middleware('permiso:modulo.seguridad.index');

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
    ->middleware('permiso:submodulo.usuario.show')
    ->middleware('can:show,usuario');//policy
    Route::get('/administrador/usuario/{usuario}/editar','Modulos\User\UserController@edit')->name('submodulo.usuario.edit')
    ->middleware('permiso:submodulo.usuario.edit')
    ->middleware('can:edit,usuario');//policy
 
    //ADMINISTRADOR ROLES VIEW
    Route::get('/administrador/rol','Modulos\Rol\RolController@index')->name('modulo.rol.index')
    ->middleware('permiso:modulo.rol.index');
    Route::get('/administrador/rol/crear','Modulos\Rol\RolController@create')->name('submodulo.rol.store')
    ->middleware('permiso:submodulo.rol.store');
    Route::get('/administrador/rol/{rol}/detalle','Modulos\Rol\RolController@show')->name('submodulo.rol.show')
    ->middleware('permiso:submodulo.rol.show')
    ->middleware('can:show,rol');//policy
    Route::get('/administrador/rol/{rol}/editar','Modulos\Rol\RolController@edit')->name('submodulo.rol.edit')
    ->middleware('permiso:submodulo.rol.edit')
    ->middleware('can:edit,rol');//policy
   
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
    Route::post('/administrador/usuario/empresa/{empresa}/rol/{rol}/store', 'Modulos\User\UserController@store')->name('submodulo.usuario.store.ajax')
    ->middleware('permiso:submodulo.usuario.store')
    ->middleware('transform.input:'. UserTransformer::class)
    ->middleware('can:user-store,rol');//policy
    Route::put('/administrador/usuario/{usuario}/empresa/{empresa}/rol/{rol}/update', 'Modulos\User\UserController@update')->name('submodulo.empresa.edit.ajax')
    ->middleware('permiso:submodulo.usuario.edit')
    ->middleware('transform.input:'. UserTransformer::class)
    ->middleware('can:update,usuario,rol');//policy 
    Route::post('/administrador/usuario/{usuario}/eliminar', 'Modulos\User\UserController@delete')->name('submodulo.usuario.delete.ajax')
    ->middleware('permiso:submodulo.usuario.delete')
    ->middleware('can:delete,usuario');//policy

    //ADMINISTRADOR ROLES
    Route::get('/administrador/roles/lista', 'Modulos\Rol\RolController@lista')->name('modulo.rol.index.ajax')
    ->middleware('permiso:modulo.rol.index');
    Route::post('/administrador/rol/store', 'Modulos\Rol\RolController@store')->name('submodulo.rol.store.ajax')
    ->middleware('permiso:submodulo.rol.store')
    ->middleware('transform.input:'. RolTransformer::class);
    Route::put('/administrador/rol/{rol}/update', 'Modulos\Rol\RolController@update')->name('submodulo.rol.edit.ajax')
    ->middleware('permiso:submodulo.rol.edit')
    ->middleware('transform.input:'. RolTransformer::class)
    ->middleware('can:update,rol');//policy
    Route::post('/administrador/rol/{rol}/eliminar', 'Modulos\Rol\RolController@delete')->name('submodulo.rol.delete.ajax')
    ->middleware('permiso:submodulo.rol.delete')
    ->middleware('can:delete,rol');//policy 

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

    //PERFIL
    Route::put('/perfil/usuario/{usuario}/update','Modulos\User\PerfilController@updatePerfil')->name('perfil.usuario.update')
    ->middleware('transform.input:'. UserTransformer::class);
    Route::post('/perfil/usuario/{usuario}/password/update','Modulos\User\PerfilController@updatePassword')->name('perfil.usuario-password.update')
    ->middleware('transform.input:'. UserTransformer::class);

    //SEGURIDAD
    Route::post('/administrador/seguridad/{parametro}/update','Modulos\Seguridad\SeguridadController@update')->name('modulo.seguridad.update.ajax');
    //->middleware('permiso:modulo.seguridad.index');

    //ADMINISTRADOR MULTICONSULTA
    Route::get('/administrador/multiconsulta/search/count','Modulos\Multiconsulta\MulticonsultaController@cantidadServicios')->name('module.multiconsulta.search.count')
        ->middleware('permiso:modulo.multiconsulta.index');
    Route::get('/administrador/multiconsulta/search','Modulos\Multiconsulta\MulticonsultaController@searchByMacAddress')->name('module.multiconsulta.search')
        ->middleware('permiso:modulo.multiconsulta.index');
   

      
});
