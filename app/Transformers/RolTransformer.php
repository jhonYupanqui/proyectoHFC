<?php

namespace App\Transformers;

use App\Administrador\Role;
use League\Fractal\TransformerAbstract;

class RolTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Role $rol)
    {
        return [
            'identificador'=> (int) $rol->id,
            'nombre'=> (string) $rol->nombre,
            'fechaCreacion'=> (string) $rol->created_at,
            'fechaActualizacion'=> (string) $rol->updated_at,
            'fechaEliminacion'=> ($rol->deleted_at == null)? null : (string) $rol->deleted_at,
        ];
    }
    
    public function originalAttribute($data){
        return [
            'identificador' => 'id',
            'nombre' = >'nombre',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at',
            '_method' => '_method',
        ];
    }
}
