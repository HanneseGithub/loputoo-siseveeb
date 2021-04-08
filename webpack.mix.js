// webpack.mix.js
let mix = require('laravel-mix');
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
    .browserSync({
        proxy: 'http://localhost:8888/naiskoor',
        files: [
            'src/**/*.svg',
            'templates/**/*.twig',
        ]
});
