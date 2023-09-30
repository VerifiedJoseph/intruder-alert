const path = require('path')
const fs = require('fs')
const fsp = require('fs/promises')
const fsExtra = require('fs-extra')

module.exports = class Helper {
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
      throw new Error('Copy failed', { cause: err })
    }
  }

  /**
   * Create folder
   */
  async createFolder (folder) {
    folder = path.resolve(folder)

    if (fs.existsSync(folder) === false) {
      await fsp.mkdir(folder, { recursive: true })
    }
  }

  /**
   * Remove folder
   */
  async removeFolder (folder) {
    folder = path.resolve(folder)

    try {
      if (fs.existsSync(folder) === true) {
        await fsp.rm(folder, { recursive: true, force: true })
      }
    } catch (err) {
      throw new Error('Folder remove failed', { cause: err })
    }
  }

  /**
   * Remove file
   */
  async removeFile (file) {
    file = path.resolve(file)

    try {
      if (fs.existsSync(file) === true) {
        await fsp.rm(file)
      }
    } catch (err) {
      throw new Error('File remove failed', { cause: err })
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
          if (err) {
            throw new Error('Symlink remove failed', { cause: err })
          }
        })
      } else if (err) {
        // console.log(err)
      }
    })
  }
}
