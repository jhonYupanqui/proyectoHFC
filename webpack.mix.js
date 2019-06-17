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
    .js('resources/js/administrador/index.js','public/js/sistema/administrador/index.js')
    //USUARIOS
    .js('resources/js/administrador/modulos/user/index.js','public/js/sistema/modulos/users/index.min.js')
    .js('resources/js/administrador/modulos/user/show.js','public/js/sistema/modulos/users/show.min.js')
    .js('resources/js/administrador/modulos/user/edit.js','public/js/sistema/modulos/users/edit.min.js')
    .js('resources/js/administrador/modulos/user/store.js','public/js/sistema/modulos/users/store.min.js')
    .js('resources/js/administrador/modulos/user/delete.js','public/js/sistema/modulos/users/delete.min.js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/login.scss', 'public/css');

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
