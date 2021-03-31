// webpack.mix.js

let mix = require('laravel-mix');

mix.sass('./static/main.scss', './style.css')
    .combine('static/js/**/*.js', 'static/site.js', true)
    .browserSync({
        proxy: 'http://localhost:8888/naiskoor',
        files: [
            'static/main.scss',
            'static/scss/**/*.scss',
            'static/site.js',
            'static/js/**/*.js',
        ]
    });
