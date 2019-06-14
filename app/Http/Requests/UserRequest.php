<?php

namespace App\Http\Requests;

use App\Administrador\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validar_update ='';
         
        if(isset($this->route('user')->id)){
            $validar_update=$this->route('user')->id>0 ?", ". $this->route('user')->id:"";
        }

        return [ 
            'nombre' => 'required|max:100|unico_compuesto:users,nombre,deleted_at'.$validar_update,
            'apellidos' => 'required|max:100|unico_compuesto:users,apellidos,deleted_at'.$validar_update,
            'dni' => 'required|max:8|unico_compuesto:users,dni,deleted_at'.$validar_update,
            'celular' => 'required|max:7',
            'email' => 'required|email|unico_compuesto:users,email,deleted_at'.$validar_update,
            'estado' => 'required|in:'.User::ESTADO_ACTIVO.','.User::ESTADO_INACTIVO,
        ];
          
    }

    public function messages()
    {
        return [
            'nombre.unico_compuesto'  => 'Ya existe un registro con el mismo nombre, intente cambiarlos.'
        ];
    }
    
}
