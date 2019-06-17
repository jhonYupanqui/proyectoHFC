<?php

namespace App\Http\Controllers\Modulos\User;

use App\Administrador\Role;
use App\Administrador\User;
use Illuminate\Http\Request;
use App\Administrador\Empresa;
use App\Administrador\Permiso;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GeneralController;

class UserController extends GeneralController
{
    public function index()
    {
        $usuarios = User::all();
        return view('administrador.modulos.user.index',["usuarios"=>$usuarios]);
    }

    public function list(Request $request)
    { 
        if($request->ajax()){
             $usuarios = User::all()->toJson(); 
            // $usuarios = DB::table('users')->get();
            //   dd($usuarios);
            //return $this->showContJsonAll($usuarios,true,true,true,true);
            /*$usuario = new User;
            return $this->showModJsonAll($usuario,true,false,true,false,true,true);*/
            return  $usuarios;
        }
        return abort(404); 
    }

    public function listAjax()
    {
        return datatables()
                ->eloquent(User::query())
                ->only(['id','nombre','apellidos','username','email','btn'])
                ->addColumn('btn', 'administrador.modulos.user.partials.acciones')
                ->rawColumns(['btn'])
                ->toJson();
        ///administrador/usuarios/lista-ajax
    }

    public function show(User $usuario)
    {
       
        return view('administrador.modulos.user.detalle',[
            "usuario"=>$this->showModJsonOne($usuario)
        ]);
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
  
        return view('administrador.modulos.user.create',[
            "empresas"=>$this->showContJsonAll($empresas),
            "roles"=>$this->showContJsonAll($roles),
            "modulos_permisos"=>$modulos_permisos,
        ]);
    }

    public function store(Empresa $empresa, Role $rol, UserRequest $request)
    {
        $usuario = new User;
       
        #Generando usuario
            $nombre=strtolower($request->nombre);
            $apellidos = strtolower($request->apellidos);

            $primeraletraNombre = substr($nombre,0,1);

            $array_apellidos=explode(" ",$apellidos);
            $apellidos_letras = $array_apellidos[0];
            
            if (count($array_apellidos) > 1) {
                for ($i=1; $i < count($array_apellidos); $i++) { 
                    $apellidos_letras .= substr($array_apellidos[$i],0,1);
                }
            }
             
            $usuario_nuevo = $primeraletraNombre.$apellidos_letras;
            //dd( $usuario_nuevo );
        #End Usuarios

        #Generando Password
            $charsPwd = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*+";
            $lengthPwd = 8;
            $nuevaPassword = substr( str_shuffle( $charsPwd ), 0, $lengthPwd );
        #End Password
             
         try { 

            //generando Usuario y Password

            DB::beginTransaction();

            $campos = $request->all();
            $campos['usuario'] = $usuario_nuevo;    
            $campos['password'] = bcrypt($nuevaPassword);
                
            // $usuario = User::create($campos); 
              
              $usuario->empresa_id = $empresa->id;
              $usuario->role_id = $rol->id;
              $usuario->nombre = $request->nombre;
              $usuario->apellidos = $request->apellidos;
              $usuario->dni = $request->dni;
              $usuario->telefono = $request->telefono;
              $usuario->email = $request->email;
              $usuario->username = $campos['usuario'];
              $usuario->password = $campos['password']; 
              $usuario->estado = User::ESTADO_ACTIVO;
              $usuario->save();
   
              $usuario->permisos()->sync($request->permisos);//crea vinculo al id del permiso
  
                
              DB::commit();
        }catch(QueryException $ex){ 
            // dd($ex->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un problema en el registro, intente nuevamente verificando que los campos estén completos!.",402);
        }catch(\Exception $e){
            // dd($e->getMessage()); 
            DB::rollback();
            return $this->errorMessage("Hubo un error inesperado!, intente nuevamente verificando que los campos estén completos!!.",402);
        }

        return response()->json(["error"=>false,"data"=>array(
            "usuario"=>$usuario_nuevo,
            "clave"=>$nuevaPassword
        )]);

    }   
}
