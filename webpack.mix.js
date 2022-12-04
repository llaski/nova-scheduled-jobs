let mix = require('laravel-mix')

require('./nova.mix')

mix.setPublicPath('dist')
    .js('resources/js/app.js', 'js')
    .vue({ version: 3 })
    .css('resources/css/app.css', 'css')
    .nova('llaski/nova-scheduled-jobs')
