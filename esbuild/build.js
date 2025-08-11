const esbuild = require('esbuild')
const Helper = require('./class/Helper.js')

const helper = new Helper()

async function setup () {
  // Remove and recreate dist folder
  await helper.removeFolder('./dist')
  await helper.createFolder('./dist')

  console.log('Copying files...')
  helper.copyFile('./frontend/index.html', './dist/index.html')
  helper.copyFile('./frontend/data.php', './dist/data.php')
  helper.copyFile('./README.md', './dist/README.md')
  helper.copyFile('./CHANGELOG.md', './dist/CHANGELOG.md')
  helper.copyFile('./LICENSE', './dist/LICENSE.md')
  await helper.copyFolder('./backend', './dist/backend')

  // Remove tests and data folders
  helper.removeFolder('./dist/backend/tests')
  helper.removeFolder('./dist/backend/data')
}

setup().then(() => {
  console.log('Running esbuild...')

  esbuild.build({
    entryPoints: ['./frontend/js/app.js'],
    bundle: true,
    minify: true,
    outdir: 'dist/static'
  })

  esbuild.build({
    entryPoints: ['./frontend/css/app.css'],
    bundle: true,
    minify: true,
    outdir: 'dist/static'
  })
})
