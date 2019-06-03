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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'empresa_id' => 'required',
            'role_id' => 'required',
            'nombre' => 'required|max:100',
            'apellidos' => 'required|max:100',
            'dni' => 'required|max:8',
            'telefono' => 'required|max:7',
            'email' => 'required|unique:email,users',
            'usuario' => 'required|unique:usuario,users',
            'clave' => 'required',
            'estado' => 'required|in:'.User::ESTADO_ACTIVO.','.User::ESTADO_INACTIVO,
        ];

         
    }
}
