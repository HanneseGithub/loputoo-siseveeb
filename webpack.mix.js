// webpack.mix.js

let mix = require('laravel-mix');

mix.sass('./static/main.scss', './style.css')
    .browserSync('localhost:8888/naiskoor');