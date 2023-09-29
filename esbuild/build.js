const esbuild = require('esbuild')
const BuildHelper = require('./buildHelper.js')

const Helper = new BuildHelper()

async function setup () {
  await Helper.removeSymlink('./dist/backend')
  await Helper.removeFolder('./dist/backend')

  await Helper.copyFile('index.html')
  await Helper.copyFile('data.php')
  await Helper.copyFolder('./backend', './dist/backend')
}

setup()

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
