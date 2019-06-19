<?php

namespace App\Http\Controllers\Modulos\Password;

use App\Administrador\User;
use Illuminate\Http\Request;
use App\Functions\UserFunctions;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public function primerCambio(){
       return view('administrador.password.primerCambio');
    }

    public function update(Request $request, User $usuario)
    {
        $rules = [
            'password'=>'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/'
        ];
    
        $this->validate($request,$rules);

        $userFunctions = new UserFunctions;
        
        try {
            DB::beginTransaction();
              #begin Transaction Update User
                 
                $usuario->password = bcrypt($request->password);
                
                $usuario->save();

                $userFunctions->registrarPasswordOld($request->password,$usuario->username);
 
              #End Begin Transaction update User
            DB::commit();
    
        }catch(QueryException $ex){ 
           // dd($ex->getMessage()); 
            DB::rollback();
            return back()
                    ->withErrors(['changePassword'=>"Hubo un problema en la actualización, intente nuevamente!."])
                    ->withInput(request(['password']));
        }catch(\Exception $e){
           // dd($e->getMessage()); 
            DB::rollback();
            return back()
                    ->withErrors(['changePassword'=>"Hubo un error inesperado!, intente nuevamente!."])
                    ->withInput(request(['password'])); 
        }

        return redirect()->route('administrador');
    }
}
