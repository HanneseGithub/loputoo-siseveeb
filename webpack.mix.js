// webpack.mix.js
let mix = require('laravel-mix');

mix.setPublicPath('static')
    .setResourceRoot('../')
    .sass('src/scss/main.scss', 'static/css/main.css')
    .combine('src/js/**/*.js', 'static/js/main.js', true)
    .browserSync({
        proxy: 'http://localhost:8888/naiskoor',
        files: [
            'static/main.scss',
            'static/scss/**/*.scss',
            'static/site.js',
            'static/js/**/*.js',
        ]
});
