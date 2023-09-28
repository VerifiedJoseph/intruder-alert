const path = require('path')
const fs = require('fs')
const fsExtra = require('fs-extra')

module.exports = class buildHelper {
  #backendSource = path.resolve('./backend')
  #backendDestination = path.resolve('./dist/backend')

  /**
   * Copy contents of backend folder to `dist/backend`
   */
  copyBackendFolder () {
    console.log(`Copying ${this.#backendSource} to ${this.#backendDestination}`)

    fsExtra.copy(this.#backendSource, this.#backendDestination, err => {
      if (err) return console.error(err)
      console.log(`Copied backend folder to ${this.#backendDestination}`)
    })
  }

  /**
   * Remove backend folder from `dist`
   */
  removeBackendFolder () {
    if (fs.existsSync(this.#backendDestination) === true) {
      fs.rmSync(this.#backendDestination, { recursive: true })
    }
  }

  /**
   * Remove backend symlink from `dist`
   */
  removeBackendSymlink () {
    fs.readlink(this.#backendDestination, (err, target) => {
      if (target !== undefined) {
        fs.unlink(this.#backendDestination, err => {
          if (err) console.log(err)
        })
      } else if (err) {
        // console.log(err)
      }
    })
  }
}
