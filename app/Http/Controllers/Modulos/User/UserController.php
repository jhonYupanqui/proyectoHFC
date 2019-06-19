<?php

namespace App\Http\Controllers\Modulos\User;

use UserFunctions;
use App\Administrador\Role;
use App\Administrador\User;
use Illuminate\Http\Request;
use App\Administrador\Empresa;
use App\Administrador\Permiso;
use App\Administrador\Parametro;
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
 
    public function lista(Request $request)
    {
        if($request->ajax()){
            return datatables()
                    ->eloquent(User::query())
                    ->only(['id','nombre','apellidos','username','email','btn'])
                    ->addColumn('btn', 'administrador.modulos.user.partials.acciones')
                    ->rawColumns(['btn'])
                    ->toJson();
        }
        return abort(404); 
    }

    public function show(User $usuario)
    { 
        $permisosGenerales = Permiso::getAllPermisosByUser($usuario);
  
        return view('administrador.modulos.user.detalle',[
            "usuario"=>$this->showModJsonOne($usuario),
            "permisos"=>$this->showContJsonAll($permisosGenerales)
        ]);
    }

    public function edit(User $usuario)
    {
        $usuarioAuth = Auth::user();

        $empresas = Empresa::all(); 
        $roles = Role::getSubRolesByRolUser($usuarioAuth);
       // $modulos_permisos = Permiso::all();//arma el esquema de modulos
 
       //Armar esquema de Modulos y permisos segùn disponga el admin o subadmin 
        $modulos = User::getModulosByUserAuth($usuarioAuth);//arma el esquema de modulos
        $permisos_role = Permiso::getPermisosRoleByUser($usuarioAuth);//arma permisos segun rol en el esquema de modulos
        $permisos_user = Permiso::getPermisosSpecialByUser($usuarioAuth);//arma permisos especiales en el esquema de modulos

        //Checked de permisos de usuario a editar
        $permisosCheckedRol = Permiso::getPermisosRoleByUser($usuario);
        $permisosCheckedUser = Permiso::getPermisosSpecialByUser($usuario);

        
        return view('administrador.modulos.user.edit',[
            "empresas"=>$this->showContJsonAll($empresas),
            "roles"=>$this->showContJsonAll($roles),
            "modulos"=>$this->showContJsonAll($modulos),
            "permisosRol"=>$this->showContJsonAll($permisos_role),
            "permisosEspeciales"=>$this->showContJsonAll($permisos_user),
            "permisosCheckedRol"=>$this->showContJsonAll($permisosCheckedRol),
            "permisosCheckedUser"=>$this->showContJsonAll($permisosCheckedUser),
            "usuario"=>$this->showModJsonOne($usuario)
        ]);
    }

    public function update(Empresa $empresa, Role $rol, User $usuario,UserRequest $request)
    {

        $userFunctions = new UserFunctions;

        if($request->filled('password')){
           $userFunctions->esValidoNuevoPassword($usuario,$request->password);//valida password
        }

        $registroActionAdmin = false;
        $oldEmpresa = $usuario->empresa_id != $empresa->id ? $usuario->empresa->nombre : "";
        $oldRol = $usuario->role_id != $rol->id ? $usuario->role->nombre : "";

        if ($usuario->empresa_id != $empresa->id || $usuario->role_id != $rol->id) {
            $registroActionAdmin = true;
        }
         
        try {
            DB::beginTransaction();
              #begin Transaction Update User
                //insertamos el password actual en el log
                if($request->filled('password')){
                    $userFunctions->registrarPasswordOld($request->password,$usuario->username);
                }
                 

                $usuario->empresa_id = $empresa->id;
                $usuario->role_id = $rol->id;
                

                if($request->filled('nombre')){ //preguntamos si mando un campo nombre y no esta vacio
                    $usuario->nombre = $request->nombre;
                }
        
                if($request->filled('apellidos')){
                    $usuario->apellidos = $request->apellidos;
                }

                if($request->filled('dni')){
                    $usuario->dni = $request->dni;
                }

                if($request->filled('telefono')){
                    $usuario->telefono = $request->telefono;
                }

                if($request->filled('email') && $usuario->email != $request->email){
                    $usuario->email = $request->email;
                }

                if($request->filled('password')){ 
                    $usuario->password = bcrypt($request->password);
                }

                if($request->filled('estado')){
                    $usuario->estado = bcrypt($request->estado);
                }
    
                if($request->filled('role_id')){
                    
                    $usuario->role_id = $request->role_id;//actualza el rol signado
                }
                
                
                $usuario->permisos()->sync($request->get('permisos'));//actualiza los permisos por usuario 
            
                $usuario->save();

                //limpia ultimos registros password manteniendo solo los ultimos 8
                $cantidad_historial = $userFunctions->cantidadHistorialPasswordByIdUser($usuario->username);
                if ($cantidad_historial > 8) { //si excede los 8 registros
                    $limit = $cantidad_historial - 8; //conservamos los 8 ultimos
                    $userFunctions->eliminarUltimoPasswordHistorial($usuario->username,$limit);
                }

                //Registrar Acciones Update del Usuario 
                if ($registroActionAdmin) { //si hubo cambios de administracion o rol y debe registrarse
                    $newEmpresa = $oldEmpresa == "" ? "" : $empresa->nombre;
                    $newRol = $oldRol == "" ? "" : $rol->nombre;
                    $userFunctions->logActionUpdateByAdmin($usuario->username,$oldEmpresa,$oldRol,$newEmpresa,$newRol);
                }
 
              #End Begin Transaction update User
            DB::commit();
    
          }catch(QueryException $ex){ 
              dd($ex->getMessage()); 
              DB::rollback();
              return $this->errorResponse(["Hubo un problema en la actualización, intente nuevamente!."],402);
          }catch(\Exception $e){
              dd($e->getMessage()); 
              DB::rollback();
              return $this->errorResponse(["Hubo un error inesperado!, intente nuevamente!."],402);
          }
 
           
           //return response()->json(['data'=>$user],200); 
            return $this->showModJsonOne($usuario);

    }

    public function create()
    {
        $usuarioAuth = Auth::user();

        $empresas = Empresa::all(); 
        $roles = Role::getSubRolesByRolUser($usuarioAuth);

        //Armar esquema de Modulos y permisos segùn disponga el admin o subadmin 
        $modulos = User::getModulosByUserAuth($usuarioAuth);//arma el esquema de modulos
        $permisos_role = Permiso::getPermisosRoleByUser($usuarioAuth);//arma permisos segun rol en el esquema de modulos
        $permisos_user = Permiso::getPermisosSpecialByUser($usuarioAuth);//arma permisos especiales en el esquema de modulos


        $modulos_permisos = Permiso::all();
  
        return view('administrador.modulos.user.create',[
            "empresas"=>$this->showContJsonAll($empresas),
            "roles"=>$this->showContJsonAll($roles),
            "modulos"=>$this->showContJsonAll($modulos),
            "permisosRol"=>$this->showContJsonAll($permisos_role),
            "permisosEspeciales"=>$this->showContJsonAll($permisos_user),
        ]);
    }

    public function store(Empresa $empresa, Role $rol, UserRequest $request)
    {
        $usuario = new User;
        $userFunctions = new UserFunctions;

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
   
            //Registro de accion Store del Admin
            $userFunctions->logActionStoreByAdmin($usuario->username,$usuario->empresa->nombre,$usuario->role->nombre);
               
                
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
    
    public function delete(User $usuario)
    {
        $usuario->permisos()->sync([]);//elimina vinculo

        $usuario->delete();

        return $this->showModJsonOne($usuario);
    }
}
