const esbuild = require('esbuild')

esbuild.build({
  entryPoints: ['static/js/index.js'],
  bundle: true,
  minify: true,
  outdir: 'dist/static'
})

esbuild.build({
  entryPoints: ['static/css/base.css'],
  bundle: true,
  minify: true,
  outdir: 'dist/static'
})
