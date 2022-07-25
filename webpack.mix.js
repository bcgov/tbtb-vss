const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    // .js('resources/js/admin-app.js', 'public/js')
    .copyDirectory('resources/images', 'public/images')
    //admin directories
    .copyDirectory('resources/js/admin', 'public/js/admin')
    .copyDirectory('resources/css/admin', 'public/css/admin')
    .copyDirectory('resources/plugins/admin', 'public/plugins/admin')
    .copyDirectory('resources/images/admin', 'public/images/admin')
    .copyDirectory('resources/fonts/admin', 'public/fonts/admin')

    .vue()
    .postCss('resources/css/app.css', 'public/css', [require('tailwindcss'), require('autoprefixer')])
    .alias({
        '@': 'resources/js',
    });

if (mix.inProduction()) {
    mix.version();
}
