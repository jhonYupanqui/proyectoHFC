<?php

namespace App\Http\Controllers\Modulos\User;

use App\Administrador\Role;
use App\Administrador\User;
use Illuminate\Http\Request;
use App\Administrador\Empresa;
use App\Administrador\Permiso;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\GeneralController;

class UserController extends GeneralController
{
    public function index()
    {
        return view('administrador.modulos.user.index');
    }

    public function list(Request $request)
    { 
        if($request->ajax()){
            // $usuarios = User::all(); 
            // $usuarios = DB::table('users')->get();
            //   dd($usuarios);
            //return $this->showContJsonAll($usuarios,true,true,true,true);
            $usuario = new User;
            return $this->showModJsonAll($usuario,true,false,true,false,true,true);
        }
        return abort(404); 
    }

    public function edit(User $usuario)
    {
        $empresas = Empresa::all(); 
        $roles = Role::all();
        $modulos_permisos = Permiso::all();

        return view('administrador.modulos.user.edit',[
            "empresas"=>$this->showContJsonAll($empresas),
            "roles"=>$this->showContJsonAll($roles),
            "modulos_permisos"=>$modulos_permisos,
            "usuario"=>$this->showModJsonOne($usuario)
        ]);
    }

    public function create()
    {
        $empresas = Empresa::all(); 
        $roles = Role::getSubRolesByRol();
        $modulos_permisos = Permiso::all();
 
        dd($roles);
        
        return view('administrador.modulos.user.create',[
            "empresas"=>$this->showContJsonAll($empresas),
            "roles"=>$this->showContJsonAll($roles),
            "modulos_permisos"=>$modulos_permisos,
        ]);
    }

    public function store(Empresa $empresa, Role $rol, Request $request)
    {
        $usuario = new User;

        //dd($request);
        $nombre=strtolower($request->nombre);
        $apellidos = strtolower($request->apellidos);

        $primeraletraNombre = substr($nombre,0,1);
        $seperadorApellidos=stripos(" ",$apellidos);

         dd($seperadorApellidos);
         /*try { 

            //generando Usuario y Password

            DB::beginTransaction();

            $campos = $request->all();
            $campos['password'] = bcrypt($request->password);
               
      
            // $usuario = User::create($campos); 
              
              $usuario->name = $request->name;
              $usuario->code = $request->code;
              $usuario->email = $request->email;
              $usuario->password = $campos['password'];
              $usuario->avatar = $campos['avatar'];
              $usuario->status = $request->status;
              $usuario->save();
  
              $usuario->roles()->sync($request->role_id);//crea vinculo al id del rol
              $usuario->permisos()->sync($request->permiso_id);//crea vinculo al id del permiso
  
               
   
              DB::commit();
        }catch(QueryException $ex){ 
            //dd($ex->getMessage()); 
            DB::rollback();
            return $this->errorResponse(["Hubo un problema en el registro, intente nuevamente!."],402);
        }catch(\Exception $e){
            //dd($e->getMessage()); 
            DB::rollback();
            return $this->errorResponse(["Hubo un error inesperado!, intente nuevamente!."],402);
        }*/

    }   
}
