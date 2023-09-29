const esbuild = require('esbuild')
const BuildHelper = require('./buildHelper.js')
const Helper = new BuildHelper()

async function setup () {
  await Helper.removeFolder('./dist/backend')
  await Helper.removeFile('./dist/index.html')

  await Helper.copy('./frontend/data.php', './dist/data.php')

  Helper.createSymlink('./backend', './dist/backend', 'dir')
  Helper.createSymlink('./frontend/index.html', './dist/index.html')
}

async function watchJs () {
  const ctx = await esbuild.context({
    entryPoints: ['frontend/js/index.js'],
    bundle: true,
    sourcemap: true,
    outdir: 'dist/static'
  })

  await ctx.watch()
  console.log('watching JavaScript files...')
}

async function watchCss () {
  const ctx = await esbuild.context({
    entryPoints: ['frontend/css/base.css'],
    bundle: true,
    outdir: 'dist/static'
  })

  await ctx.watch()
  console.log('watching CSS files...')
}

setup().then(() => {
  console.log('Running esbuild watcher...')

  watchJs()
  watchCss()
})
