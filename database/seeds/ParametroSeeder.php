<?php

use Illuminate\Database\Seeder;
use App\Administrador\Parametro;

class ParametroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parametro = new Parametro;
        $parametro->period = 30;
        $parametro->time = "dia";
        $parametro->description = "Se establece el periodo de dias maximo de cambio de contraseña.";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 30;
        $parametro->time = "dia";
        $parametro->description = "Se establece el periodo de dias para la generacion del reporte de los usuarios que no ingresan al sistema.";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 5;
        $parametro->time = "intentos";
        $parametro->description = "Se establece la cantidad de intentos de logueo.";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 30;
        $parametro->time = "minutos";
        $parametro->description = "Se establece el tiempo minimo de reactivacion de la cuenta.";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 35;
        $parametro->time = "dia";
        $parametro->description = "Se establece el periodo de dias para establecer el bloqueo por inactividad de la cuenta.";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 1;
        $parametro->time = "hora";
        $parametro->description = "Se establece el tiempo para inhabilitar el cambio de contraseña.";
        $parametro->save();
        $parametro = new Parametro;
        $parametro->period = 30;
        $parametro->time = "minutos";
        $parametro->description = "Se establece el tiempo de cierre de sesion por inactividad.";
        $parametro->save();
    }
}
