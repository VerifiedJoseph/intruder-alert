const esbuild = require('esbuild')
const fs = require('fs')
const path = require('path')

const backendSource = path.resolve('./backend')
const backendDestination = path.resolve('./dist/backend')

// Remove dist/backend folder
if (fs.existsSync(backendDestination) === true) {
  fs.rmSync(backendDestination, { recursive: true })
}

// Create symlink to backend in dist/backend
fs.symlink(
  backendSource,
  backendDestination,
  'dir', (err) => err && console.log(err)
)

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
