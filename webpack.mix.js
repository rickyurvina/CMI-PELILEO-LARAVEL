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

mix

    // Template assets
    .copy('resources/assets/vendor/template', 'public/vendor/template')
    .copy('resources/assets/img', 'public/img')

    // Web assets
    .copy('resources/assets/css/app.css', 'public/css')
    .js('resources/assets/js/app.js', 'public/js');