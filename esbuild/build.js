const esbuild = require('esbuild')
const Helper = require('./class/Helper.js')

const helper = new Helper()

async function setup () {
  console.log('Copying files...')

  // Remove symlinks
  await helper.removeSymlink('./dist/backend')
  await helper.removeFolder('./dist/backend')

  // Copy frontend and backend files
  await helper.copy('./frontend/index.html', './dist/index.html')
  await helper.copy('./frontend/data.php', './dist/data.php')
  await helper.copy('./README.md', './dist/README.md')
  await helper.copy('./LICENSE', './dist/LICENSE.md')
  await helper.copy('./backend', './dist/backend')

  // Remove tests and data folders
  await helper.removeFolder('./dist/backend/tests')
  await helper.removeFolder('./dist/backend/data')
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
