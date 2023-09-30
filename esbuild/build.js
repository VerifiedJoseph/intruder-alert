const esbuild = require('esbuild')
const Helper = require('./class/Helper.js')

const helper = new Helper()

async function setup () {
  // Remove and recreate dist folder
  await helper.removeFolder('./dist')
  await helper.createFolder('./dist')

  console.log('Copying files...')
  // Copy frontend and backend files
  helper.copy('./frontend/index.html', './dist/index.html')
  helper.copy('./frontend/data.php', './dist/data.php')
  helper.copy('./README.md', './dist/README.md')
  helper.copy('./LICENSE', './dist/LICENSE.md')
  await helper.copy('./backend', './dist/backend')

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
