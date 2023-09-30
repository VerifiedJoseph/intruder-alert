const esbuild = require('esbuild')
const Helper = require('./class/Helper.js')
const helper = new Helper()

async function setup () {
  // Remove and recreate dist folder
  await helper.removeFolder('./dist')
  await helper.createFolder('./dist')

  helper.createSymlink('./backend', './dist/backend', 'dir')
  helper.createSymlink('./frontend/data.php', './dist/data.php')
  helper.createSymlink('./frontend/index.html', './dist/index.html')
}

async function watchJs () {
  const ctx = await esbuild.context({
    entryPoints: ['frontend/js/app.js'],
    bundle: true,
    sourcemap: true,
    outdir: 'dist/static'
  })

  await ctx.watch()
  console.log('watching JavaScript files...')
}

async function watchCss () {
  const ctx = await esbuild.context({
    entryPoints: ['frontend/css/app.css'],
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
