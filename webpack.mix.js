// webpack.mix.js
let mix = require('laravel-mix');
require('dotenv').config();

const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');

mix.webpackConfig({
    plugins: [
        new SVGSpritemapPlugin('src/icons/**/*.svg', {
            output: {
                filename: 'svg/icons.svg.php',
            },
            sprite: {
                generate: {
                    title: false
                }
            }
        })
    ]
});

mix.setPublicPath('static')
    .setResourceRoot('../')
    .sass('src/scss/main.scss', 'static/css/main.css')
    .combine('src/js/**/*.js', 'static/js/main.js', true)
    .copy('src/images/', 'static/images/', false)
    .browserSync({
        proxy: process.env.DEVELOPMENT_URL,
        files: [
            'src/**/*',
            'templates/**/*.twig',
        ],
        injectChanges: false
});
