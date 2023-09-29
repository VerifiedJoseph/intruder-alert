const esbuild = require('esbuild')
const Helper = require('./class/Helper.js')

const helper = new Helper()

async function setup () {
  console.log('Copying files...')

  await helper.removeSymlink('./dist/backend')
  await helper.removeFolder('./dist/backend')

  await helper.copy('./frontend/index.html', './dist/index.html')
  await helper.copy('./frontend/data.php', './dist/data.php')
  await helper.copy('./README.md', './dist/README.md')
  await helper.copy('./LICENSE', './dist/LICENSE.md')
  await helper.copy('./backend', './dist/backend')
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
