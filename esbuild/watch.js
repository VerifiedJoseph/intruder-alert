const esbuild = require('esbuild')

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
