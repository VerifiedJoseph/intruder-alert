const esbuild = require('esbuild')
const fs = require('fs')
const fsExtra = require('fs-extra')
const path = require('path')

const backendSource = path.resolve('./backend')
const backendDestination = path.resolve('./dist/backend')

function copyBackend () {
  console.log(`Copying ${backendSource} to ${backendDestination}`)

  fsExtra.copy(backendSource, backendDestination, function (err) {
    if (err) return console.error(err)
    console.log(`Copied backend folder to ${backendDestination}`)
  })
}

// Remove symlink and copy backend folder
fs.readlink(backendDestination, (err, target) => {
  if (target !== undefined) {
    fs.unlink(backendDestination, err => {
      if (err) console.log(err)
    })

    copyBackend()
  } else if (err) {
    // console.log(err)
  }
})

if (fs.existsSync(backendDestination) === false) {
  copyBackend()
}

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
