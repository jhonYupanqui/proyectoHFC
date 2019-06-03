<?php

use App\Administrador\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permisoModulo = new Role;
        $permisoModulo->nombre = "ADMIN";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CALL";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CCM1";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CGM1";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CORE";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CRITICOS";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "INGENIERIA";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "PEXT";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "EXTRA";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "COM";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CALL101";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "NOC";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "SEGU";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "ATTDIF";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "EECC";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CDC";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "NOCEXT";
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "SEGURIDAD";
        $permisoModulo->save();
    }
}
