const esbuild = require('esbuild')
const BuildHelper = require('./buildHelper.js')

const Helper = new BuildHelper()

async function setup () {
  console.log('Copying files...')

  await Helper.removeSymlink('./dist/backend')
  await Helper.remove('./dist/backend')

  await Helper.copy('./frontend/index.html', './dist/index.html')
  await Helper.copy('./frontend/data.php', './dist/data.php')
  await Helper.copy('./backend', './dist/backend')
}

setup().then(() => {
  console.log('Running esbuild...')

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
})
