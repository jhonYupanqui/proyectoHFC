<?php

namespace App\Administrador;

use App\Administrador\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use SoftDeletes;

    protected $table = 'empresas';

    protected $fillable = [
        'nombre'
    ];

    public function users(){
        return $this->hasMany(User::class);
    }
    
}
