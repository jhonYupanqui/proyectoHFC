<?php

namespace App\Transformers;

use App\Administrador\Empresa;
use League\Fractal\TransformerAbstract;

class EmpresaTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Empresa $empresa)
    {
        return [
            'identificador'=> (int) $empresa->id,
            'nombre'=> (string) $empresa->nombre,
            'fechaCreacion'=> (string) $empresa->created_at,
            'fechaActualizacion'=> (string) $empresa->updated_at,
            'fechaEliminacion'=> ($empresa->deleted_at == null)? null : (string) $empresa->deleted_at,
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
