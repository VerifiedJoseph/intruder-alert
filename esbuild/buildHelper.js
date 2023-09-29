const path = require('path')
const fs = require('fs')
const fsp = require('fs/promises')
const fsExtra = require('fs-extra')

module.exports = class buildHelper {
  /**
   * Copy file or folder
   * @param {string} source Source
   * @param {string} destination Destination
   */
  async copy (source, destination) {
    source = path.resolve(source)
    destination = path.resolve(destination)

    console.log(`Copying ${source} to ${destination}`)

    try {
      await fsExtra.copy(source, destination)
    } catch (err) {
      console.error(err)
    }
  }

  /**
   * Remove file or folder
   */
  async remove (file) {
    file = path.resolve(file)

    try {
      if (fs.existsSync(file) === true) {
        await fsp.rm(file)
      }
    } catch (err) {
      console.error(err)
    }
  }

  /**
   * Create symlink
   */
  createSymlink (source, destination, type = 'file') {
    source = path.resolve(source)
    destination = path.resolve(destination)

    if (fs.existsSync(destination) === false) {
      fs.symlink(
        source,
        destination,
        type, (err) => err && console.log(err)
      )
    }
  }

  /**
   * Remove symlink
   */
  async removeSymlink (symlink) {
    symlink = path.resolve(symlink)

    fs.readlink(symlink, (err, target) => {
      if (target !== undefined) {
        fs.unlink(symlink, err => {
          if (err) console.log(err)
        })
      } else if (err) {
        // console.log(err)
      }
    })
  }
}
