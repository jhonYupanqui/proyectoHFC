<?php

namespace App\Administrador;

use Illuminate\Database\Eloquent\Model;
use App\Transformers\ParametroTransformer;

class Parametro extends Model
{
      
    public $transformer = ParametroTransformer::class;

    const DIAS_CAMBIO_PASSWORD = 1;
    const DIAS_REPORTE_USUARIO_SIN_ACCEDER = 2;
    const INTENTOS_MAXIMOS_LOGIN = 3;
    const MINUTOS_REACTIVACION_LOGIN = 4;
    const DIAS_BLOQUEO_INACTIVIDAD_CUENTA = 5;
    const MINUTOS_INHABILITAR_CAMBIO_PASSWORD= 6; //Se esta evaluando aun esta opcion 
    const MINUTOS_INACTIVIDAD_SESION = 7;

    //Parametros de olgura
    const ANUNCIO_DIAS_CAMBIO_PASSWORD = 10; //Anuncio a mostrar con N° X dias antes de que venza

    protected $table = 'parametros';
    
    protected $fillable = [
        'period',
        'time',
        'description'
    ];

    public static function getDiasCambioPassword()
    {
        return Parametro::find(Parametro::DIAS_CAMBIO_PASSWORD)->period;
    }

    public static function getDiasReporteUsuarioSinAcceder()
    {
        return Parametro::find(Parametro::DIAS_REPORTE_USUARIO_SIN_ACCEDER)->period;
    }

    public static function getIntentosMaximosLogin()
    {
        return Parametro::find(Parametro::INTENTOS_MAXIMOS_LOGIN)->period;
    }

    public static function getMinutosReactivacionLogin()
    {
        return Parametro::find(Parametro::MINUTOS_REACTIVACION_LOGIN)->period;
    }

    public static function getDiasInactividadCuenta()
    {
        return Parametro::find(Parametro::DIAS_BLOQUEO_INACTIVIDAD_CUENTA)->period;
    }

    public static function getMinutosInhabilitarCambioPassword()
    {
        return Parametro::find(Parametro::MINUTOS_INHABILITAR_CAMBIO_PASSWORD)->period;
    }

    public static function getMinutosBloqueoInactivadSession()
    {
        return Parametro::find(Parametro::MINUTOS_INACTIVIDAD_SESION)->period;
    }
 
}
