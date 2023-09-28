const esbuild = require('esbuild')
const BuildHelper = require('./buildHelper.js')

const Helper = new BuildHelper()
Helper.removeBackendSymlink()
Helper.removeBackendFolder()
Helper.copyBackendFolder()

esbuild.build({
  entryPoints: ['./frontend/js/index.js'],
  bundle: true,
  minify: true,
  outdir: 'dist/static'
})

esbuild.build({
  entryPoints: ['./frontend/css/base.css'],
  bundle: true,
  minify: true,
  outdir: 'dist/static'
})
