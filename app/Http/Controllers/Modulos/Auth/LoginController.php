<?php

namespace App\Http\Controllers\Modulos\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index(){
        return view('auth.login');
    }

    public function login(Request $request){
 
         
        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];
    
        $credentials = $this->validate($request,$rules);

        $credentials["estado"] = "A";
         
        if(Auth::attempt($credentials)){
          return redirect()->route('administrador');
        }
        
        return back()
                ->withErrors(['auth'=>trans('auth.failed'),"username"=>"problemas con el ususario"])
                ->withInput(request(['username','auth']));
        
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
