let mix = require('laravel-mix')
let path = require('path')

require('./nova.mix')

mix.setPublicPath('dist')
    .js('resources/js/app.js', 'js')
    .vue({ version: 3 })
    .nova('llaski/nova-scheduled-jobs');