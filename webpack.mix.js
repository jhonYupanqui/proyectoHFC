const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    //ADMINISTRADOR
    .js('resources/js/administrador/index.js','public/js/sistema/administrador/index.js')
    //USUARIOS
    .js('resources/js/administrador/modulos/user/index.js','public/js/sistema/modulos/users/index.min.js')
    .js('resources/js/administrador/modulos/user/show.js','public/js/sistema/modulos/users/show.min.js')
    .js('resources/js/administrador/modulos/user/edit.js','public/js/sistema/modulos/users/edit.min.js')
    .js('resources/js/administrador/modulos/user/store.js','public/js/sistema/modulos/users/store.min.js')
    .js('resources/js/administrador/modulos/user/delete.js','public/js/sistema/modulos/users/delete.min.js')
    //ROLES
    .js('resources/js/administrador/modulos/role/index.js','public/js/sistema/modulos/roles/index.min.js')
    .js('resources/js/administrador/modulos/role/show.js','public/js/sistema/modulos/roles/show.min.js')
    .js('resources/js/administrador/modulos/role/edit.js','public/js/sistema/modulos/roles/edit.min.js')
    .js('resources/js/administrador/modulos/role/edit-admin.js','public/js/sistema/modulos/roles/edit-admin.min.js')
    .js('resources/js/administrador/modulos/role/store.js','public/js/sistema/modulos/roles/store.min.js')
    .js('resources/js/administrador/modulos/role/store-admin.js','public/js/sistema/modulos/roles/store-admin.min.js')
    .js('resources/js/administrador/modulos/role/delete.js','public/js/sistema/modulos/roles/delete.min.js')
    //EMPRESAS
    .js('resources/js/administrador/modulos/empresa/index.js','public/js/sistema/modulos/empresas/index.min.js')
    .js('resources/js/administrador/modulos/empresa/show.js','public/js/sistema/modulos/empresas/show.min.js')
    .js('resources/js/administrador/modulos/empresa/edit.js','public/js/sistema/modulos/empresas/edit.min.js')
    .js('resources/js/administrador/modulos/empresa/store.js','public/js/sistema/modulos/empresas/store.min.js')
    .js('resources/js/administrador/modulos/empresa/delete.js','public/js/sistema/modulos/empresas/delete.min.js')
    //PERFIL
    .js('resources/js/administrador/perfil/perfil.js','public/js/sistema/perfil/perfil.min.js')
    //SEGURIDAD
    .js('resources/js/administrador/modulos/seguridad/index.js','public/js/sistema/modulos/seguridad/index.min.js')
    //MULTiCONSULTA
    .js('resources/js/administrador/modulos/multiconsulta/index.js','public/js/sistema/modulos/multiconsulta/index.min.js')

    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/multiconsulta.scss', 'public/css/modulos/multiconsulta.css')
    .sass('resources/sass/login.scss', 'public/css')
    .sass('resources/sass/bootstrap.scss', 'public/css');

mix.webpackConfig({
    resolve: {
        alias: {
            "@": path.resolve(
                __dirname,
                "resources/js"
            )
        }
    }
    });
