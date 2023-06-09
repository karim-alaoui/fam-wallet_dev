const elixir = require('cakephp3-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Extensions
 |--------------------------------------------------------------------------
 |
 | Elixir provides several extensions which bring new functionality to Elixir.
 | A couple of examples have been included below to get you started.
 | You can use any extension made for the most recent version of Elixir 6.
 | Feel free to try out different extensions!
 | Please report any problems to: https://github.com/pfuri/cakephp-elixir/issues
 |
 */
//require('laravel-elixir-del');
//require('laravel-elixir-webpack-official');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
elixir(mix => {
  mix
    .sass([
      'foundation/*.scss',
      'layout/*.scss',
      'object/**/*.scss',
      'object/**/**/*.scss',
      'app.scss',
    ], 'webroot/css/app.css')
    .scripts([
      'test.js',
    ], './webroot/js/app.js')
});
