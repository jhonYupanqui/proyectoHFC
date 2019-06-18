<?php

namespace App\Administrador;

use Illuminate\Database\Eloquent\Model;
use App\Transformers\ParametroTransformer;

class Parametro extends Model
{
      
    public $transformer = ParametroTransformer::class;

    protected $table = 'parametros';
    
    protected $fillable = [
        'period',
        'time',
        'description'
    ];
 
}
