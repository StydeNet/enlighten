let mix = require('laravel-mix');

const tailwindcss = require('tailwindcss')

mix.babel('resources/js/prism.js', 'dist/js/prism.js')
    .js('resources/js/app.js', 'dist/js/app.js')
    .combine(
        [
            'dist/js/prism.js',
            'dist/js/app.js'
        ],  'dist/js/build.js');

mix.less('resources/less/app.less', 'dist/css')
    .options({
        postCss: [
            tailwindcss('./tailwind.config.js'),
        ]
    });

mix.copy('dist', 'bootstrap/dist');