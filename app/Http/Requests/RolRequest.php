<?php

namespace App\Http\Requests;

use App\Administrador\Role;
use Illuminate\Foundation\Http\FormRequest;

class RolRequest extends FormRequest
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

        // dd(request()->all());

        $validar_update ='';
         
        if(isset($this->route('rol')->id)){
            $validar_update=$this->route('rol')->id>0 ?", ". $this->route('rol')->id:"";
        }

        return [ 
            'nombre'=>'required|max:50|unico_compuesto:roles,nombre,deleted_at'.$validar_update,
            'especial'=>'nullable|in: '.Role::CON_PERMISOS_TOTAL.','.Role::SIN_PERMISOS_TOTAL,
            'referencia'=>'nullable|numeric|id_bd:roles,referencia',
        ];
 
       
    }

    public function messages()
    {
        return [
            'nombre.unico_compuesto'  => 'Nombre ya existente en los datos',
            'referencia.id_bd'  => 'No se encontrarón datos relacionados al referencia. verifique su selección.',
        ];
    }

}
