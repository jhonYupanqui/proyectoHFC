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
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CALL";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CCM1";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CGM1";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CORE";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CRITICOS";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "INGENIERIA";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "PEXT";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "EXTRA";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "COM";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CALL101";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "NOC";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "SEGU";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "ATTDIF";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "EECC";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "CDC";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "NOCEXT";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
        $permisoModulo = new Role;
        $permisoModulo->nombre = "SEGURIDAD";
        $permisoModulo->especial = Role::SIN_PERMISOS_TOTAL;
        $permisoModulo->save();
    }
}
