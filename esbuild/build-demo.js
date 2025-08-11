const esbuild = require('esbuild')
const Helper = require('./class/Helper.js')
const helper = new Helper()

async function setup () {
  await helper.removeFolder('./dist')
  await helper.createFolder('./dist')

  await helper.copyFile('./frontend/index.html', './dist/index.html');
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
}).catch((error) => {
  console.error(error);
})
