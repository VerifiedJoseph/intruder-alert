const esbuild = require('esbuild')
const fs = require('fs')
const path = require('path')

fs.readlink(path.resolve('./dist/backend'), (err, target) => {
  if (target === undefined) {
    fs.symlink(
      path.resolve('./backend'),
      path.resolve('./dist/backend'),
      'dir', (err) => err && console.log(err)
    )
  } else if (err) {
    console.log(err)
  }
})

async function watchJs () {
  const ctx = await esbuild.context({
    entryPoints: ['frontend/js/index.js'],
    bundle: true,
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

watchJs()
watchCss()
