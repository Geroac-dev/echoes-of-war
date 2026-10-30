const mix = require('laravel-mix');
const webpack = require('webpack');

mix.js('resources/js/app.js', 'public/js')
   .vue()
   .sass('resources/sass/app.scss', 'public/css')
   .webpackConfig({
       plugins: [
           new webpack.DefinePlugin({
               __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(true),
           }),
       ],
   });

if (mix.inProduction()) mix.version();