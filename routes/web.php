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
    Route::get('/empresas','Modulos\Empresa\EmpresaController@index')->name('modulo.empresas.index')
    ->middleware('permiso:modulo.rol.index');
    //ADMINISTRADOR PRINCIPAL JSON
    Route::post('/administrador/lista', 'AdministradorController@list')->name('administrador.list');
     

});
