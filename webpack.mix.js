let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .styles([
        'resources/assets/css/skeleton/css/normalize.css',
        'resources/assets/css/skeleton/css/skeleton.css',
        // 'resources/assets/basscss/css/bassapp.css',
        'resources/assets/css/app.css'
    ], 'public/css/app.css');


   // .sass('resources/assets/sass/app.scss', 'public/css');
