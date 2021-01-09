let mix = require('laravel-mix');

const tailwindcss = require('tailwindcss')

mix.babel('resources/js/prism.js', 'dist/js/prism.js')
    .js('resources/js/app.js', 'dist/js/app.js')
    .combine(
        [
            'dist/js/prism.js',
            'dist/js/app.js'
        ],  'dist/js/build.js');

mix.postCss('resources/css/app.css', 'dist/css', [
    tailwindcss('./tailwind.config.js'),
]);
